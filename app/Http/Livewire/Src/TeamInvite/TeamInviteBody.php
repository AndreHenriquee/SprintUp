<?php

namespace App\Http\Livewire\Src\TeamInvite;

use App\Mail\InviteLinkEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class TeamInviteBody extends Component
{
    public $routeParams;
    public $teamDataAndPermission;
    public $squads, $roles, $permissionGroups, $nowDateTime;
    public $squadId, $roleId, $permissionGroupId, $email;
    public $squadName, $roleName, $permissionGroupName;
    public $inviteLink;
    public $wasLinkCopied = false;

    public function render()
    {
        $this->teamDataAndPermission = $this->fetchTeamDataAndPermission(session('user_data'));
        $this->squads = $this->fetchUserSquads();
        $this->roles = $this->fetchRoles();
        $this->permissionGroups = $this->fetchPermissionGroups();
        $this->nowDateTime = now();

        return view('livewire.src.team-invite.team-invite-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.nome
                , p.permitido AS permissao_link_convite
                , tp.nome AS permissao_link_convite_nome
            FROM equipe e
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE e.id = ?
                AND eu.usuario_id = ?
                AND tp.referencia = "[TEAM] CREATE_INVITE_LINK"
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    private function fetchUserSquads()
    {
        $squadsQuery = <<<SQL
            SELECT
                id
                , nome
            FROM squad
            WHERE equipe_id = ?
        SQL;

        return DB::select(
            $squadsQuery,
            [$this->routeParams['equipe_id']],
        );
    }

    private function fetchRoles()
    {
        $rolesQuery = <<<SQL
            SELECT
                id
                , nome
            FROM cargo
        SQL;

        return DB::select($rolesQuery);
    }

    private function fetchPermissionGroups()
    {
        $permissionGroupsQuery = <<<SQL
            SELECT
                id
                , nome
            FROM grupo_permissao
            WHERE equipe_id IS NULL
                OR equipe_id = ?
        SQL;

        return DB::select(
            $permissionGroupsQuery,
            [$this->routeParams['equipe_id']],
        );
    }

    private function validatePermissionManagement(array $sessionParams)
    {
        $permissionManagementQuery = <<<SQL
            SELECT
                tp.referencia AS permissao_referencia
                , IF (
                    tp.referencia = "[TEAM] MNG_MODERATORS_AND_COMMONS",
                    p.permitido,
                    NULL
                ) AS permissao_moderador_comum
                , IF (
                    tp.referencia = "[TEAM] MNG_ADMINISTRATORS",
                    p.permitido,
                    NULL
                ) AS permissao_administrador
            FROM equipe_usuario eu
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE eu.equipe_id = ?
                AND eu.usuario_id = ?
                AND tp.referencia IN (
                    "[TEAM] MNG_MODERATORS_AND_COMMONS",
                    "[TEAM] MNG_ADMINISTRATORS"
                )
        SQL;

        $permissionManagement = DB::select(
            $permissionManagementQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['usuario_id'],
            ],
        );

        $groupedPermissions = [];

        foreach ($permissionManagement as $pm) {
            if ($pm->permissao_referencia == '[TEAM] MNG_MODERATORS_AND_COMMONS') {
                $groupedPermissions['permissao_moderador_comum'] = (int) $pm->permissao_moderador_comum;

                continue;
            }

            if ($pm->permissao_referencia == '[TEAM] MNG_ADMINISTRATORS') {
                $groupedPermissions['permissao_administrador'] = (int) $pm->permissao_administrador;

                continue;
            }
        }

        if ((int) $this->permissionGroupId == 1) {
            return $groupedPermissions['permissao_administrador'];
        }

        return $groupedPermissions['permissao_moderador_comum'];
    }

    private function dataValidated(bool $includeEmailValidation = false)
    {
        $rules = [
            'squadId' => 'required|integer|gt:0',
            'roleId' => 'required|integer|gt:0',
            'permissionGroupId' => 'required|integer|gt:0',
        ];

        if ($includeEmailValidation) {
            $rules['email'] = 'required|email:rfc,dns';
        }

        $this->validate($rules);

        if (!$this->validatePermissionManagement(session('user_data'))) {
            $this->emit('alertNoPermission');

            return false;
        }

        return true;
    }

    private function generateInviteLink()
    {
        $this->inviteLink = md5(
            implode(' | ', [
                'squadId: ' . $this->squadId,
                'roleId: ' . $this->roleId,
                'permissionGroupId: ' . $this->permissionGroupId,
                'dateTime: ' . $this->nowDateTime,
            ])
        );
    }

    private function registerInviteLink()
    {
        $existingHash = DB::selectOne(
            <<<SQL
                SELECT
                    id
                FROM link_convite
                WHERE hash = ?
            SQL,
            [$this->inviteLink]
        );

        if (empty($existingHash)) {
            DB::table('link_convite')->insert([
                [
                    'hash' => $this->inviteLink,
                    'ativo' => 1,
                    'copiado' => $this->wasLinkCopied,
                    'emails_enviados' => trim($this->email),
                    'squad_id' => $this->squadId,
                    'cargo_id' => $this->roleId,
                    'grupo_permissao_id' => $this->permissionGroupId,
                ]
            ]);
        }
    }

    private function getNameFromSelection(array $options, int $selected)
    {
        foreach ($options as $o) {
            if ($o['id'] == $selected) {
                return $o['nome'];
            }
        }
    }

    private function sendEmail()
    {
        $teamName = $this->teamDataAndPermission['nome'];
        $squadName = $this->getNameFromSelection($this->squads, (int) $this->squadId);
        $roleName = $this->getNameFromSelection($this->roles, (int) $this->roleId);
        $permissionGroupName = $this->getNameFromSelection($this->permissionGroups, (int) $this->permissionGroupId);

        Mail::to(trim($this->email))
            ->send(
                new InviteLinkEmail([
                    'equipe_nome' => $teamName,
                    'squad_nome' => $squadName,
                    'cargo_nome' => $roleName,
                    'grupo_permissao_nome' => $permissionGroupName,
                    'email' => trim($this->email),
                    'hash' => $this->inviteLink,
                ])
            );
    }

    public function copyLink()
    {
        if (self::dataValidated()) {
            $this->wasLinkCopied = true;

            if (empty($this->inviteLink)) {
                $this->generateInviteLink();
            }

            $this->registerInviteLink();
            $this->emit('copyLinkToClipboard', $this->inviteLink);
        }
    }

    public function sendLinkByEmail()
    {
        if (self::dataValidated(true)) {
            if (empty($this->inviteLink)) {
                $this->generateInviteLink();
            }

            $this->registerInviteLink();
            $this->sendEmail();
            $this->emit('inviteLinkSendedToEmail');
        }
    }
}

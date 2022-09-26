<?php

namespace App\Http\Livewire\Src\TeamInvite;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeamInviteBody extends Component
{
    public $routeParams;
    public $teamDataAndPermission;
    public $squads, $roles, $permissionGroups, $nowDateTime;
    public $squadId, $roleId, $permissionGroupId, $email;
    public $inviteLink;

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

    public function copyLink()
    {
        if (self::dataValidated()) {
            $this->generateInviteLink();
            // $this->registerInviteLink();
            $this->emit('copyLinkToClipboard', $this->inviteLink);
        }
    }

    public function sendLinkByEmail()
    {
        if (self::dataValidated(true)) {
            // $this->registerInviteLink();
            $this->generateInviteLink();
        }
    }
}

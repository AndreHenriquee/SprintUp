<?php

namespace App\Http\Livewire\Src\TeamMembers;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeamMembersBody extends Component
{
    public $routeParams;
    public $sessionParams, $teamDataAndPermission;
    public $teamMembers;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        $this->teamMembers = self::fetchTeamMembers((int) $this->routeParams['equipe_id'], (int) $this->sessionParams['usuario_id']);

        return view('livewire.src.team-members.team-members-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.nome
                , (
                    SELECT
                        p.permitido
                    FROM permissao p
                    JOIN tipo_permissao tp
                        ON p.tipo_permissao_id = tp.id
                    WHERE p.grupo_permissao_id = eu.grupo_permissao_id
                        AND tp.referencia = '[USER_ACCESS] MNG_MODERATORS_AND_COMMONS_ROLES'
                ) AS permissao_moderador_comum
                , (
                    SELECT
                        p2.permitido
                    FROM permissao p2
                    JOIN tipo_permissao tp2
                        ON p2.tipo_permissao_id = tp2.id
                    WHERE p2.grupo_permissao_id = eu.grupo_permissao_id
                        AND tp2.referencia = '[USER_ACCESS] MNG_ADMINISTRATORS_ROLES'
                ) AS permissao_administrador
            FROM equipe e
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            WHERE e.id = ?
                AND eu.usuario_id = ?
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    private static function fetchTeamMembers(int $teamId, int $loggedUserId)
    {
        $teamMembersQuery = <<<SQL
            SELECT
                u.id
                , u.nome
                , u.email
                , gp.id AS grupo_permissao_id
                , gp.nome AS grupo_permissao_nome
            FROM usuario u
            JOIN equipe_usuario eu
                ON u.id = eu.usuario_id
            JOIN grupo_permissao gp
                ON eu.grupo_permissao_id = gp.id
            WHERE eu.equipe_id = ?
            ORDER BY IF (u.id = ?, 1, 0) DESC
        SQL;

        return (array) DB::select(
            $teamMembersQuery,
            [$teamId, $loggedUserId]
        );
    }
}

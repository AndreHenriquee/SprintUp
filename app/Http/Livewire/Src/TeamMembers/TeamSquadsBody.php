<?php

namespace App\Http\Livewire\Src\TeamMembers;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeamSquadsBody extends Component
{
    public $routeParams;
    public $sessionParams, $teamDataAndPermission;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        $this->teamSquads = self::fetchTeamSquads((int) $this->routeParams['equipe_id'], (int) $this->sessionParams['squad_id']);

        return view('livewire.src.team-members.team-squads-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.nome
                , eu.grupo_permissao_id
                , p.permitido AS permissao_gerenciar_squads
            FROM equipe e
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE e.id = ?
                AND eu.usuario_id = ?
                AND tp.referencia = "[WORKFLOW] MNG_SQUADS"
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    private static function fetchTeamSquads(int $teamId, int $loggedSquadId)
    {
        $teamSquadsQuery = <<<SQL
            SELECT
                id
                , referencia
                , nome
                , descricao
            FROM squad
            WHERE equipe_id = ?
                AND (
                    excluida <> 1
                    OR excluida IS NULL
                )
            ORDER BY IF (id = ?, 1, 0) DESC,
                nome ASC
        SQL;

        return (array) DB::select(
            $teamSquadsQuery,
            [$teamId, $loggedSquadId]
        );
    }
}

<?php

namespace App\Http\Livewire\Src\Team;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TeamBody extends Component
{
    public $userTeams;

    public function render()
    {
        $this->userTeams = self::fetchUserTeams(session('user_data'));

        return view('livewire.src.team.team-body');
    }

    private static function fetchUserTeams(array $sessionParams)
    {
        $userTeamsQuery = <<<SQL
            SELECT
                e.id
                , e.nome
                , e.descricao
                , IF (e.roadmap_ativo = 1, "Sim", "Não") AS roadmap_ativo
                , IF (
                    gp.equipe_id IS NULL,
                    gp.nome,
                    CONCAT(gp.nome, " | Comum")
                ) AS grupo_permissao
                , p.permitido AS permissao_link_convite
            FROM equipe_usuario eu
            JOIN equipe e
                ON eu.equipe_id = e.id
            JOIN grupo_permissao gp
                ON eu.grupo_permissao_id = gp.id
            JOIN permissao p
                ON gp.id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE eu.usuario_id = ?
                AND tp.referencia = "[TEAM] CREATE_INVITE_LINK"
        SQL;

        return DB::select(
            $userTeamsQuery,
            [$sessionParams['usuario_id']]
        );
    }
}

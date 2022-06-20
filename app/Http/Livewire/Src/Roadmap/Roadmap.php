<?php

namespace App\Http\Livewire\Src\Roadmap;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Roadmap extends Component
{
    public $routeParams;
    public $teamData;
    public $features;

    public function render()
    {
        $this->teamData = self::fetchTeamData(
            $this->routeParams['equipe_id'] ?? null,
            session('user_data')
        );

        $this->features = ['TO_DO' => [], 'DOING' => [], 'DONE' => []];

        if (isset($this->teamData['id'])) {
            $this->features = self::fetchFeatures($this->teamData['id']);
        };

        return view('livewire.src.roadmap.roadmap');
    }

    private function fetchTeamData($teamId, array $sessionParams)
    {
        if (!$teamId) {
            $teamBySquadQuery = <<<SQL
                SELECT
                    e.id
                    , e.nome
                FROM equipe e
                JOIN squad s
                    ON e.id = s.equipe_id
                    AND s.id = ?
            SQL;

            return (array) DB::selectOne(
                $teamBySquadQuery,
                [$sessionParams['squad_id']],
            );
        }

        $teamByIdQuery = <<<SQL
            SELECT
                id
                , nome
            FROM equipe
            WHERE id = ?
        SQL;

        return (array) DB::selectOne(
            $teamByIdQuery,
            [$teamId],
        );
    }

    private static function fetchFeatures(int $equipe_id)
    {
        $featuresQuery = <<<SQL
            SELECT
                f.id
                , e.nome AS equipe_nome
                , (
                    CASE
                        WHEN f.data_inicio > NOW()
                            THEN "TO_DO"
                        WHEN f.data_inicio <= NOW() AND f.finalizada = 0 AND f.porcentagem_conclusao < 100
                            THEN "DOING"
                        ELSE "DONE"
                    END
                ) AS `status`
                -- Pré-visualização
                , f.nome
                , f.imagem
                , f.data_inicio
                , f.data_fim
                , f.porcentagem_conclusao
                , f.finalizada
                -- Detalhamento da funcionalidade
                , f.descricao
                , f.data_hora_replanejamento
                , f.produto_id
                , p.id AS produto_id
                , p.nome AS produto_nome
                , p.imagem AS produto_imagem
            FROM funcionalidade f
            JOIN produto p
                ON f.produto_id = p.id
            JOIN equipe e
                ON p.equipe_id = e.id
                AND e.id = ?
                AND e.roadmap_ativo = 1
            ORDER BY f.data_fim DESC
        SQL;

        $features = DB::select(
            $featuresQuery,
            [$equipe_id],
        );

        $groupedFeatures = ['TO_DO' => [], 'DOING' => [], 'DONE' => []];

        foreach ($features as $feature) {
            $groupedFeatures[$feature->status][] = $feature;
        }

        return $groupedFeatures;
    }
}

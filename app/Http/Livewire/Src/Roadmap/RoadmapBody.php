<?php

namespace App\Http\Livewire\Src\Roadmap;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RoadmapBody extends Component
{
    protected $listeners = ['validateRouteParams'];

    public $alias;
    public $routeParams;

    public $sessionParams, $teamDataAndPermission;

    public $teamData;
    public $features;
    public $products;

    public $selectedProductId;

    public function render()
    {
        $this->sessionParams = session('user_data');

        if (!empty($this->sessionParams)) {
            $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        }

        $this->teamData = self::fetchTeamData(
            $this->routeParams['equipe_id'] ?? null,
            $this->sessionParams
        );

        $this->features = ['TO_DO' => [], 'DOING' => [], 'DONE' => []];

        if (isset($this->teamData['id'])) {
            $this->features = $this->fetchFeatures($this->teamData['id']);
            $this->products = self::fetchProcucts($this->teamData['id']);
        };

        return view('livewire.src.roadmap.roadmap-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.id
                , e.nome
                , p.permitido AS permissao_gerenciar_produtos
                , c.referencia AS cargo
            FROM equipe e
            JOIN squad s
                ON e.id = s.equipe_id
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN squad_usuario su
                ON s.id = su.squad_id
                AND eu.usuario_id = su.usuario_id
            JOIN cargo c
                ON su.cargo_id = c.id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE s.id = ?
                AND eu.usuario_id = ?
                AND tp.referencia = "[ROADMAP] MNG_PRODUCTS"
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $sessionParams['squad_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    public function validateRouteParams()
    {
        $updateFilters = false;

        if (
            !in_array($this->routeParams['produto_id'], ['', 'null']) &&
            !in_array((int) $this->routeParams['produto_id'], array_column($this->products, 'id'))
        ) {
            $this->selectedProductId = 0;
            $updateFilters = true;
        }

        if ($updateFilters) {
            $this->updateFilter();
        }
    }

    public function updateFilter()
    {
        if (!(int) $this->selectedProductId) {
            return redirect(
                '/' . implode('/', [$this->alias, $this->alias == 'roadmap' ? '' : $this->teamData['id']])
            );
        }

        if ($this->alias == 'roadmap') {
            return redirect(
                '/' . implode('/', [$this->alias, $this->selectedProductId])
            );
        }

        return redirect(
            '/' . implode('/', [$this->alias, $this->teamData['id'], $this->selectedProductId])
        );
    }

    private static function fetchTeamData($teamId, $sessionParams)
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
                WHERE (
                    s.excluida <> 1
                    OR s.excluida IS NULL
                )
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

    private function fetchFeatures(int $equipe_id)
    {
        $filter = '';

        if ($this->routeParams['produto_id']) {
            $filter .= <<<SQL
                AND p.id = ?
            SQL;

            $params[] = $this->routeParams['produto_id'];
        }

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
                {$filter}
            JOIN equipe e
                ON p.equipe_id = e.id
                AND e.id = ?
                AND e.roadmap_ativo = 1
            WHERE (
                p.excluido <> 1
                OR p.excluido IS NULL
            )
            ORDER BY p.nome ASC, f.data_fim DESC
        SQL;

        $params[] = $equipe_id;

        $features = DB::select($featuresQuery, $params);

        $groupedFeatures = ['TO_DO' => [], 'DOING' => [], 'DONE' => []];

        foreach ($features as $feature) {
            $groupedFeatures[$feature->status]['product-' . $feature->produto_id]['product-name'] = $feature->produto_nome;
            $groupedFeatures[$feature->status]['product-' . $feature->produto_id]['product-features'][] = $feature;
        }

        return $groupedFeatures;
    }

    private static function fetchProcucts(int $equipe_id)
    {
        $productsQuery = <<<SQL
            SELECT
                id
                , nome
            FROM produto
            WHERE equipe_id = ?
                AND (
                    excluido <> 1
                    OR excluido IS NULL
                )
            ORDER BY nome ASC
        SQL;

        return (array) DB::select(
            $productsQuery,
            [$equipe_id],
        );
    }
}

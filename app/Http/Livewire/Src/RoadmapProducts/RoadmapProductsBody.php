<?php

namespace App\Http\Livewire\Src\RoadmapProducts;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RoadmapProductsBody extends Component
{
    public $routeParams;

    public $sessionParams, $teamDataAndPermission;

    public $teamProducts;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);

        $this->teamProducts = self::fetchTeamProducts((int) $this->routeParams['equipe_id']);

        return view('livewire.src.roadmap-products.roadmap-products-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.id
                , e.nome
                , eu.grupo_permissao_id
                , p.permitido AS permissao_gerenciar_produtos
            FROM equipe e
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE e.id = ?
                AND eu.usuario_id = ?
                AND tp.referencia = "[ROADMAP] MNG_PRODUCTS"
                AND p.permitido = 1
        SQL;

        $teamData = (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['usuario_id'],
            ],
        );

        if (!empty($teamData)) {
            $permissionsToManageItemsQuery = <<<SQL
                SELECT
                    tp.referencia
                    , p.permitido
                FROM permissao p
                JOIN tipo_permissao tp
                    ON p.tipo_permissao_id = tp.id
                WHERE p.grupo_permissao_id = ?
                    AND tp.referencia IN (
                        "[ROADMAP] MNG_ROADMAP_ITEMS"
                        , "[ROADMAP] MNG_ROADMAP_ITEMS_STATUS"
                    )
            SQL;

            $permissionsToManageItems = DB::select(
                $permissionsToManageItemsQuery,
                [$teamData['grupo_permissao_id']]
            );

            $permissionMap = [
                "[ROADMAP] MNG_ROADMAP_ITEMS" => 'permissao_gerenciar_funcionalidades',
                "[ROADMAP] MNG_ROADMAP_ITEMS_STATUS" => 'permissao_gerenciar_status_funcionalidades',
            ];

            foreach ($permissionsToManageItems as $p) {
                $teamData[$permissionMap[$p->referencia]] = $p->permitido;
            }
        }

        return $teamData;
    }

    private function fetchTeamProducts(int $teamId)
    {
        $teamProductsQuery = <<<SQL
            SELECT
                p.id
                , p.nome
                , p.descricao
                , COUNT(f.id) AS numero_funcionalidades
            FROM produto p
            LEFT JOIN funcionalidade f
                ON p.id = f.produto_id
            WHERE p.equipe_id = ?
                AND (
                    p.excluido <> 1
                    OR p.excluido IS NULL
                )
                AND (
                    f.excluida <> 1
                    OR f.excluida IS NULL
                )
            GROUP BY p.id, p.nome, p.descricao
        SQL;

        return DB::select(
            $teamProductsQuery,
            [$teamId],
        );
    }
}

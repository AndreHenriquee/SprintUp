<?php

namespace App\Http\Livewire\Src\RoadmapProducts;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RoadmapNewProductBody extends Component
{
    public $routeParams;
    public $sessionParams, $teamDataAndPermission;
    public $nomeProduto, $descProduto;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);

        return view('livewire.src.roadmap-products.roadmap-new-product-body');
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
                AND tp.referencia = "[ROADMAP] MNG_PRODUCTS"
                AND p.permitido = 1
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'nomeProduto' => 'required|min:3|max:50',
                'descProduto' => 'max:1000',
            ],
            [
                'nomeProduto.required' => 'Nome do produto é obrigatório',
                'nomeProduto.min' => 'Nome do produto precisa ter no mínimo 3 caracteres',
                'nomeProduto.max' => 'Nome do produto precisa ter no máximo 50 caracteres',
                'descProduto.max' => 'Descrição do produto pode ter no máximo 1000 caracteres',
            ],
        );

        return true;
    }

    public function registerProduct()
    {
        if ($this->dataValidated()) {
            DB::table('produto')->insert([
                'nome' => $this->nomeProduto,
                'descricao' => $this->descProduto,
                'equipe_id' => $this->routeParams['equipe_id'],
            ]);

            return redirect('/roadmap-produtos/' . $this->routeParams['equipe_id']);
        }
    }

    public function resetFields()
    {
        $this->nomeProduto = '';
        $this->descProduto = '';
    }
}

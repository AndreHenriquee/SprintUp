<?php

namespace App\Http\Livewire\Src\Roadmap;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Roadmap extends Component
{
    public $data;
    public $products;

    public function render()
    {
        $this->data = self::fetchProductData(session(['1']));
        $this->products = self::fetchProducts((int) $this->data['id']);

        return view('livewire.src.roadmap.roadmap');
    }

    private static function fetchProductData()
    {
        $dataQuery = <<<SQL
            SELECT
                c.id id
                , c.nome
            FROM cliente c
            WHERE c.id = 1
        SQL;

        return (array) DB::selectOne(
            $dataQuery,

        );
    }

    /*private static function fetchProducts(int $productId)
    {
        $columnsQuery = <<<SQL
            SELECT
                f.id
                , f.nome
                , f.descricao
                , f.imagem
                , f.data_inicio
                , f.data_fim
                , f.porcentagem_conclusao
                , f.finalizada
                , f.data_hora_replanejamento
                , f.produto_id
                , p.id
                , p.nome
                , p.equipe_id
            FROM funcionalidade f
            INNER JOIN produto p
                ON f.id = ?
        SQL;

        return DB::select(
            $columnsQuery,
            [$productId],
        );
    }*/
}

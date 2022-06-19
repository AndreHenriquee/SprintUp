<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ColunaConteudo extends Component
{
    public $columnData;
    public $cards;

    public function render()
    {
        $this->cards = self::fetchColumnCards((int) $this->columnData['coluna_id']);

        return view('livewire.src.kanban.coluna-conteudo');
    }

    public static function fetchColumnCards(int $columnId)
    {
        $cardsQuery = <<<SQL
            SELECT
                t.id
                -- Pré-visualização
                , t.referencia
                , t.titulo
                , est.estimativa
                , est.forma
                , est.extensao
                , us1.foto AS usuario_responsavel_foto
                , us1.nome AS usuario_responsavel_nome
                -- Detalhamento da tarefa
                , t.detalhamento
                , t.data_hora_criacao
                , t.data_hora_ultima_movimentacao
                , col.nome AS nome_coluna
                , t.prioridade
                , us2.foto AS usuario_relator_foto
                , us2.nome AS usuario_relator_nome
            FROM tarefa t
            INNER JOIN coluna col
                ON t.coluna_id = col.id
            INNER JOIN usuario us1
                ON t.responsavel_id = us1.id
            INNER JOIN usuario us2
                ON t.relator_id = us2.id
            INNER JOIN estimativa_tarefa est
                ON t.estimativa_tarefa_id = est.id
            WHERE col.id = ?
        SQL;

        return DB::select(
            $cardsQuery,
            [$columnId]
        );
    }
}

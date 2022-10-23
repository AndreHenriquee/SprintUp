<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ColunaConteudo extends Component
{
    public $columnData, $allColumns;
    public $cards;
    public $wipHitted;
    public $backlogCards;
    public $alias;

    public function render()
    {
        $this->cards = $this->alias == "kanban" ? self::fetchColumnCards((int) $this->columnData['id']) : self::fetchBacklogColumnCards((int) $this->columnData['id']);
        $this->wipHitted = self::isWipHitted(count($this->cards), (int) $this->columnData['wip']);

        return view('livewire.src.kanban.coluna-conteudo');
    }

    private static function fetchColumnCards(int $columnId)
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
                , us1.id AS usuario_responsavel_id
                , us1.foto AS usuario_responsavel_foto
                , us1.nome AS usuario_responsavel_nome
                , us1.email AS usuario_responsavel_email
                , t.coluna_id AS tarefa_status
                -- Detalhamento da tarefa
                , t.detalhamento AS detalhamento 
                , t.data_hora_criacao
                , t.data_hora_ultima_movimentacao
                , col.id AS id_coluna
                , col.nome AS nome_coluna
                , t.prioridade AS prioridade
                , us2.foto AS usuario_relator_foto
                , us2.nome AS usuario_relator_nome
                , sp.numero
                , sp.inicio
                , sp.fim
            FROM tarefa t
            LEFT JOIN coluna col
                ON t.coluna_id = col.id
            LEFT JOIN usuario us1
                ON t.responsavel_id = us1.id
            JOIN usuario us2
                ON t.relator_id = us2.id
            LEFT JOIN estimativa_tarefa est
                ON t.estimativa_tarefa_id = est.id
            JOIN sprint sp
                ON t.sprint_id = sp.id
            WHERE col.id = ?
                AND NOW() BETWEEN sp.inicio AND sp.fim
                AND sp.finalizada = 0
            ORDER BY t.prioridade ASC
        SQL;

        return DB::select(
            $cardsQuery,
            [$columnId]
        );
    }

    private static function fetchBacklogColumnCards(int $columnId)
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
                , us1.id AS usuario_responsavel_id
                , us1.foto AS usuario_responsavel_foto
                , us1.nome AS usuario_responsavel_nome
                , us1.email AS usuario_responsavel_email
                , t.coluna_id AS tarefa_status
                -- Detalhamento da tarefa
                , t.detalhamento AS detalhamento 
                , t.data_hora_criacao
                , t.data_hora_ultima_movimentacao
                , col.id AS id_coluna
                , col.nome AS nome_coluna
                , t.prioridade AS prioridade
                , us2.foto AS usuario_relator_foto
                , us2.nome AS usuario_relator_nome
                , sp.numero
                , sp.inicio
                , sp.fim
            FROM tarefa t
            LEFT JOIN coluna col
                ON t.coluna_id = col.id
            LEFT JOIN usuario us1
                ON t.responsavel_id = us1.id
            INNER JOIN usuario us2
                ON t.relator_id = us2.id
            LEFT JOIN estimativa_tarefa est
                ON t.estimativa_tarefa_id = est.id
            LEFT JOIN sprint sp
                ON t.sprint_id = sp.id
            WHERE col.id = ?
            ORDER BY t.prioridade ASC
        SQL;

        return DB::select(
            $cardsQuery,
            [$columnId]
        );
    }

    private static function isWipHitted(int $cardsNumber, ?int $columnWip)
    {
        if (!$columnWip) {
            return false;
        }

        return $cardsNumber > $columnWip;
    }
}

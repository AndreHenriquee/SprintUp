<?php

namespace App\Http\Livewire\Src\Kanban;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class KanbanBody extends Component
{
    public $data;
    public $columns;

    public function render()
    {
        $this->data = self::fetchKanbanData(session('user_data'));
        $this->columns = self::fetchColumns((int) $this->data['id']);

        return view('livewire.src.kanban.kanban-body');
    }

    private static function fetchKanbanData(array $sessionParams)
    {
        $dataQuery = <<<SQL
            SELECT
                qk.id
                , qk.nome
                , qk.descricao
            FROM quadro_kanban qk
            JOIN squad_usuario su
                ON qk.squad_id = su.squad_id
            WHERE su.usuario_id = ?
                AND su.squad_id = ?;
        SQL;

        return (array) DB::selectOne(
            $dataQuery,
            [$sessionParams['usuario_id'], $sessionParams['squad_id']],
        );
    }

    private static function fetchColumns(int $quadroKanbanId)
    {
        $columnsQuery = <<<SQL
            SELECT
                id AS coluna_id
                , nome
                , descricao
                , inicio_tarefa
                , fim_tarefa
                , wip
            FROM coluna
            WHERE quadro_kanban_id = ?
            ORDER BY ordem ASC;
        SQL;

        return DB::select(
            $columnsQuery,
            [$quadroKanbanId],
        );
    }
}

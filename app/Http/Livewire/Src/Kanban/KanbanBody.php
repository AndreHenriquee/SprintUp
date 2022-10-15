<?php

namespace App\Http\Livewire\Src\Kanban;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class KanbanBody extends Component
{
    public $alias;
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
        $sessionParams = session('user_data');
        $columnsQuery = <<<SQL
             SELECT
                c.id
                , c.nome as nome
                , c.descricao as descricao
                , c.inicio_tarefa
                , c.fim_tarefa
                , c.wip
                , qk.squad_id as coluna_id
            FROM coluna c
            INNER JOIN quadro_kanban qk
                ON c.quadro_kanban_id = qk.id
            WHERE qk.id = ?
                AND qk.squad_id = ?
                AND c.ordem <> 0
            ORDER BY c.ordem ASC;
        SQL;

        return DB::select(
            $columnsQuery,
            [$quadroKanbanId, $sessionParams['squad_id']],
        );
    }
}

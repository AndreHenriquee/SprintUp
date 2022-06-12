<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class KanbanBody extends Component
{
    public $data;

    public function render()
    {
        $dataQuery = <<<MySQL_QUERY
            SELECT
                nome
                , descricao
            FROM quadro_kanban
            WHERE id = 1;
        MySQL_QUERY;

        $data = DB::select($dataQuery);

        $this->data = $data;

        return view('livewire.src.kanban.kanban-body');
    }
}

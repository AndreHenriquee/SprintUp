<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;

class ColunaCabecalho extends Component
{
    public $columnData;

    public function render()
    {
        return view('livewire.src.kanban.coluna-cabecalho');
    }
}

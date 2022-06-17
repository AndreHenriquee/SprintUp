<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;

class ColunaCabecalho extends Component
{
    public $coluna_id;
    public $nome;
    public $descricao;
    public $inicio_tarefa;
    public $fim_tarefa;
    public $wip;

    public function render()
    {
        return view('livewire.src.kanban.coluna-cabecalho');
    }
}

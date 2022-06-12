<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;

class Coluna extends Component
{
    public $nome;

    public function render()
    {
        return view('livewire.src.kanban.coluna');
    }
}

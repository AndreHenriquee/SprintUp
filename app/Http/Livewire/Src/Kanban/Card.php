<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;

class Card extends Component
{
    public $data;

    public function render()
    {
        return view('livewire.src.kanban.card');
    }
}

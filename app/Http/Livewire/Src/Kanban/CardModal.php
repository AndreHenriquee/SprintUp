<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;


class CardModal extends Component
{
    public $data;

    public function render()
    {
        return view('livewire.src.kanban.card-modal');
    }
}

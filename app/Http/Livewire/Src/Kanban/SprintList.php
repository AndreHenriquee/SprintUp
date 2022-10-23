<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;

class SprintList extends Component
{
    public $sprintDetails;

    public function render()
    {
        return view('livewire.src.kanban.sprint-list');
    }
}

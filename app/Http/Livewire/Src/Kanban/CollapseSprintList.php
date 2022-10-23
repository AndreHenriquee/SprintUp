<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;

class CollapseSprintList extends Component
{
    public $sprintDetails;

    public function render()
    {
        return view('livewire.src.kanban.collapse-sprint-list');
    }
}

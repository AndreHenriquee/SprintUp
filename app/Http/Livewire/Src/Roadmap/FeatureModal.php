<?php

namespace App\Http\Livewire\Src\Roadmap;

use Livewire\Component;

class FeatureModal extends Component
{
    public $data;
    public $status;

    public function render()
    {
        return view('livewire.src.roadmap.feature-modal');
    }
}

<?php

namespace App\Http\Livewire\Src\Roadmap;

use Livewire\Component;

class FeatureList extends Component
{
    public $features;
    public $tipo;
    public $typeMap;

    public function render()
    {
        return view('livewire.src.roadmap.feature-list');
    }
}

<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;

class Head extends Component
{
    public $title;

    public function render()
    {
        return view('livewire.page.head');
    }
}

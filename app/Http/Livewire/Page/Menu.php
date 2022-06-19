<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;

class Menu extends Component
{
    public $alias;

    public function render()
    {
        return view('livewire.page.menu');
    }
}

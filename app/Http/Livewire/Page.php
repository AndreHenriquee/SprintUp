<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Page extends Component
{
    public $alias;
    public $title;
    public $body;
    public $loadMenu;

    public function render()
    {
        return view('livewire.page');
    }
}

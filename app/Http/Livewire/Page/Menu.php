<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;

class Menu extends Component
{
    public $pageAlias;

    public function render()
    {
        return view('livewire.page.menu');
    }
}

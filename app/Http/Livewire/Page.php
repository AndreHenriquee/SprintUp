<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Page extends Component
{
    public $title;
    public $body;
    public $pageAlias;

    public function render()
    {
        return view('livewire.page');
    }
}

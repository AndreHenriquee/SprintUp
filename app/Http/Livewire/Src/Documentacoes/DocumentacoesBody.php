<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Livewire\Component;

class DocumentacoesBody extends Component
{
    public $alias;

    public function render()
    {
        return view('livewire.src.documentacoes.documentacoes-body');
    }
}

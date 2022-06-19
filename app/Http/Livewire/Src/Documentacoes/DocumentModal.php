<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Livewire\Component;

class DocumentModal extends Component
{
    public $data;
    public $typeMap;

    public function render()
    {
        return view('livewire.src.documentacoes.document-modal');
    }
}

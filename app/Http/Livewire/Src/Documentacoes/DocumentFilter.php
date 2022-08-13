<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Livewire\Component;

class DocumentFilter extends Component
{
    
    public $queryFilters = "";
    protected $listeners;


    public function addData($selectedFilter) {

        if($selectedFilter) {
            $queryFilters .= <<<SQL
                AND tipo_documento = $selectedFilter
            SQL;
        }

        return $queryFilters;
    }


    public function render()
    {
        return view('livewire.src.documentacoes.document-filter');
    }

    
}

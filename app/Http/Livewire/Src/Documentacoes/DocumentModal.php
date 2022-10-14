<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentModal extends Component
{
    public $teamDataAndPermission, $data, $typeMap;

    public $docTitle, $docContent;

    public function render()
    {
        return view('livewire.src.documentacoes.document-modal');
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'docTitle' => 'required|min:3|max:100',
                'docContent' => 'nullable|min:10|max:50000',
            ],
            [
                'docTitle.required' => 'O título é obrigatório',
                'docTitle.min' => 'O título precisa ter no mínimo 3 caracteres',
                'docTitle.max' => 'O título pode ter no máximo 100 caracteres',
                'docContent.min' => 'O conteúdo precisa ter no mínimo 10 caracteres',
                'docContent.max' => 'O conteúdo pode ter no máximo 10 mil caracteres',
            ],
        );

        return true;
    }

    private function doesDataChanged()
    {
        if (
            trim($this->data['titulo']) <> trim($this->docTitle)
            || trim($this->data['conteudo']) <> trim($this->docContent)
        ) {
            return true;
        }

        return false;
    }

    public function saveChanges()
    {
        if ($this->dataValidated()) {
            if ($this->doesDataChanged()) {
                DB::table('documentacao')
                    ->where('id', (int) $this->data['id'])
                    ->update([
                        'titulo' => trim($this->docTitle),
                        'conteudo' => trim($this->docContent),
                    ]);

                return redirect('/documentacoes');
            }

            $this->emit('noDataChanged-' . $this->data['id']);
        }
    }
}

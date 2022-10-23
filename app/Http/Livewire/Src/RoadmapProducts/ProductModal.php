<?php

namespace App\Http\Livewire\Src\RoadmapProducts;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductModal extends Component
{
    public $data, $teamId;

    public $productName, $productDescription;

    public function render()
    {
        return view('livewire.src.roadmap-products.product-modal');
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'productName' => 'required|min:3|max:50',
                'productDescription' => 'max:250',
            ],
            [
                'productName.required' => 'Nome do produto é obrigatório',
                'productName.min' => 'Nome do produto precisa ter no mínimo 3 caracteres',
                'productName.max' => 'Nome do produto precisa ter no máximo 50 caracteres',
                'productDescription.max' => 'Descrição do produto precisa ter no máximo 250 caracteres',
            ],
        );

        return true;
    }

    private function doesDataChanged()
    {
        if (
            trim($this->data['nome']) <> trim($this->productName)
            || trim($this->data['descricao']) <> trim($this->productDescription)
        ) {
            return true;
        }

        return false;
    }

    public function saveChanges()
    {
        if ($this->dataValidated()) {
            if ($this->doesDataChanged()) {
                DB::table('produto')
                    ->where('id', (int) $this->data['id'])
                    ->update([
                        'nome' => trim($this->productName),
                        'descricao' => trim($this->productDescription),
                    ]);

                return redirect('/roadmap-produtos/' . $this->teamId);
            }

            $this->emit('noDataChanged-' . $this->data['id']);
        }
    }
}

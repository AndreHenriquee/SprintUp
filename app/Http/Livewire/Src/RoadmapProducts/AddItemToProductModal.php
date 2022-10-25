<?php

namespace App\Http\Livewire\Src\RoadmapProducts;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AddItemToProductModal extends Component
{
    public $productData, $allowedToChangeStatus;

    public $itemName, $itemDescription, $initialDate, $endDate, $conclusionPercentage = 0, $isFinalized;
    public $minDate = '1900-01-01';

    public function render()
    {
        return view('livewire.src.roadmap-products.add-item-to-product-modal');
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'itemName' => 'required|min:3|max:50',
                'itemDescription' => 'nullable|min:3|max:250',
                'initialDate' => 'required|date|after_or_equal:minDate|before_or_equal:endDate',
                'endDate' => 'required|date|after_or_equal:initialDate',
            ],
            [
                'itemName.required' => 'Nome do produto é obrigatório',
                'itemName.min' => 'Nome do produto precisa ter no mínimo 3 caracteres',
                'itemName.max' => 'Nome do produto pode ter no máximo 50 caracteres',
                'itemDescription.min' => 'Descrição do produto precisa ter no mínimo 3 caracteres',
                'itemDescription.max' => 'Descrição do produto pode ter no máximo 250 caracteres',
                'initialDate.required' => 'O início do desenvolvimento é obrigatório',
                'initialDate.date' => 'O início do desenvolvimento precisa ter uma data válida',
                'initialDate.after_or_equal' => 'O início do desenvolvimento precisa ter uma data realista',
                'initialDate.before_or_equal' => 'O início do desenvolvimento ser antes da data do release',
                'endDate.required' => 'A data de release é obrigatória',
                'endDate.date' => 'A data de release precisa ser válida',
                'endDate.after_or_equal' => 'A data de release precisa ser após o início da data de desenvolvimento',
            ],
        );

        return true;
    }

    public function createItem()
    {
        if ($this->dataValidated()) {
            DB::table('funcionalidade')->insert([
                'nome' => trim($this->itemName),
                'descricao' => trim($this->itemDescription),
                'data_inicio' => $this->initialDate,
                'data_fim' => $this->endDate,
                'porcentagem_conclusao' => $this->conclusionPercentage,
                'finalizada' => empty($this->isFinalized) ? 0 : $this->isFinalized,
                'produto_id' => $this->productData['id'],
            ]);

            return redirect(request()->header('Referer'));
        }
    }
}

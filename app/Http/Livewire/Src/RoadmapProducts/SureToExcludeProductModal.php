<?php

namespace App\Http\Livewire\Src\RoadmapProducts;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SureToExcludeProductModal extends Component
{
    public $productData, $numberOfProducts;

    public function render()
    {
        return view('livewire.src.roadmap-products.sure-to-exclude-product-modal');
    }

    public function excludeProduct()
    {
        DB::update(
            "UPDATE produto SET excluido = 1 WHERE id = ?",
            [(int) $this->productData['id']]
        );

        return redirect(request()->header('Referer'));
    }
}

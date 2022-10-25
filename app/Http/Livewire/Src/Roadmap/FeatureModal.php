<?php

namespace App\Http\Livewire\Src\Roadmap;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FeatureModal extends Component
{
    public $listeners = [];

    public $teamDataAndPermission, $data, $status;

    public $featureName, $featureDescription, $initialDate, $endDate, $conclusionPercentage, $isFinalized;
    public $minDate = '1900-01-01';

    public function mount()
    {
        $this->featureName = $this->data['nome'];
        $this->featureDescription = $this->data['descricao'];
        $this->conclusionPercentage = $this->data['porcentagem_conclusao'];
        $this->isFinalized = $this->data['finalizada'];
    }

    public function render()
    {
        $this->listeners = ['excludeItem-' . $this->data['id'] => 'excludeItem'];

        return view('livewire.src.roadmap.feature-modal');
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'featureName' => 'required|min:3|max:50',
                'featureDescription' => 'nullable|min:3|max:250',
                'initialDate' => 'required|date|after_or_equal:minDate|before_or_equal:endDate',
                'endDate' => 'required|date|after_or_equal:initialDate',
            ],
            [
                'featureName.required' => 'Nome do produto é obrigatório',
                'featureName.min' => 'Nome do produto precisa ter no mínimo 3 caracteres',
                'featureName.max' => 'Nome do produto pode ter no máximo 50 caracteres',
                'featureDescription.min' => 'Descrição do produto precisa ter no mínimo 3 caracteres',
                'featureDescription.max' => 'Descrição do produto pode ter no máximo 250 caracteres',
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

    public function excludeItem()
    {
        DB::table('funcionalidade')
            ->where('id', (int) $this->data['id'])
            ->update(['excluida' => 1]);

        return redirect(request()->header('Referer'));
    }

    public function saveChanges()
    {
        if ($this->dataValidated()) {
            DB::table('funcionalidade')
                ->where('id', $this->data['id'])
                ->update([
                    'nome' => trim($this->featureName),
                    'descricao' => trim($this->featureDescription),
                    'data_inicio' => $this->initialDate,
                    'data_fim' => $this->endDate,
                    'porcentagem_conclusao' => $this->conclusionPercentage,
                    'finalizada' => $this->isFinalized,
                    'data_hora_replanejamento' => date('Y-m-d H:i:s'),
                ]);

            return redirect(request()->header('Referer'));
        }
    }
}

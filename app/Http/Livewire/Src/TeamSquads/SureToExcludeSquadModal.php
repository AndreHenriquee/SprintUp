<?php

namespace App\Http\Livewire\Src\TeamSquads;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SureToExcludeSquadModal extends Component
{
    public $squadData, $numberOfSquads;

    public function render()
    {
        return view('livewire.src.team-squads.sure-to-exclude-squad-modal');
    }

    public function excludeSquad()
    {
        DB::update(
            "UPDATE squad SET excluida = 1 WHERE id = ?",
            [(int) $this->squadData['id']]
        );

        return redirect(request()->header('Referer'));
    }
}

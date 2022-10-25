<?php

namespace App\Http\Livewire\Src\Roadmap;

use Livewire\Component;

class CollapseRoadmapSection extends Component
{
    public $teamDataAndPermission;
    public $features;
    public $tipo;
    public $typeMap;

    public function render()
    {
        $this->typeMap = self::fetchTypeMap();

        return view('livewire.src.roadmap.collapse-roadmap-section');
    }

    private static function fetchTypeMap()
    {
        return [
            'TO_DO' => [
                'titulo' => 'No radar',
                'descricao' => 'Funcionalidades que a equipe já identificou e irá desenvolver em breve',
            ],
            'DOING' => [
                'titulo' => 'Em desenvolvimento',
                'descricao' => 'Funcionalidades que a equipe está desenvolvendo nesse momento',
            ],
            'DONE' => [
                'titulo' => 'Finalizadas',
                'descricao' => 'Funcionalidades que a equipe já terminou de desenvolver',
            ],
        ];
    }
}

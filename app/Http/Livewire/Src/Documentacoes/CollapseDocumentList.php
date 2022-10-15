<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Livewire\Component;

class CollapseDocumentList extends Component
{
    public $tipo;
    public $textFilter;
    public $taskMentionIdFilter;
    public $memberMentionIdFilter;
    public $dateFilter;
    public $teamDataAndPermission;

    public $typeMap;
    public $unifiedFilters;

    public function render()
    {
        $this->typeMap = self::fetchTypeMap();

        $this->unifiedFilters = [
            'textFilter' => $this->textFilter == 'null' ? '' : $this->textFilter,
            'taskMentionIdFilter' => $this->taskMentionIdFilter == 'null' ? '' : $this->taskMentionIdFilter,
            'memberMentionIdFilter' => $this->memberMentionIdFilter == 'null' ? '' : $this->memberMentionIdFilter,
            'dateFilter' => $this->dateFilter == 'null' ? '' : $this->dateFilter,
        ];

        return view('livewire.src.documentacoes.collapse-document-list');
    }

    private static function fetchTypeMap()
    {
        return [
            'INFORMATION' => [
                'titulo' => 'Informações',
                'descricao' => 'Documentação sobre alguma informação geral que a Equipe ou a Squad acha importante registrar',
            ],
            'SPRINT_PLANNING' => [
                'titulo' => 'Sprint Planning',
                'descricao' => 'Planejamento feito das atividades para a próxima Sprint',
            ],
            'DAILY_SCRUM' => [
                'titulo' => 'Daily Scrum',
                'descricao' => 'Reuniões diárias sobre o andamento das tarefas da Sprint',
            ],
            'SPRINT_REVIEW' => [
                'titulo' => 'Sprint Review',
                'descricao' => 'Observação das entregas feitas na Sprint que passou',
            ],
            'SPRINT_RETROSPECTIVE' => [
                'titulo' => 'Sprint Retrospective',
                'descricao' => 'Reflexão sobre os pontos positivos e negativos da Sprint que passou, buscando melhorar na próxima',
            ],
        ];
    }
}

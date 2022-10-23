<?php

namespace App\Http\Livewire\Src\Backlog;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateSprint extends Component
{
    public $routeParams;
    public $teamDataAndPermission;
    public $sessionParams;
    public $startDate;
    public $endDate;
    public $selectedCards = [];
    public $cardsArray = [];
    public $selection;
    protected $listeners = ['addCards' => 'addCards'];
    protected $rules = [
        'selectedCards.{$id]' =>'nullable'
    ];

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        $this->data = self::fetchKanbanData(session('user_data'));
        $this->column = self::fetchColumn((int) $this->data['id']);
        $this->cards = self::fetchColumnCards((int) $this->column['id']);
        return view('livewire.src.backlog.create-sprint');
    }

    public function addCards()
    {   
        if (self::fieldsValidation() && $this->selectedCards != null) {
            $countSprints = self::countSquadSprints();
            $taskStart = self::getStartColumn();
            $preparedCards = self::prepareCards();
            $sprintNumber = $countSprints['quantidade_sprint'] + 1;
            $taskId = array_keys($preparedCards);

            $sprintId = DB::table('sprint')->insertGetId([
                'numero' => $sprintNumber, 
                'inicio' => $this->startDate,
                'fim' => $this->endDate,
                'finalizada' => 0,
                'squad_id' => $this->sessionParams['squad_id']
            ]);

            foreach ($taskId as $id) {
                DB::table('tarefa')->where('id', $id)->update([
                    'sprint_id' => $sprintId,
                    'coluna_id' => $taskStart['id'],
                    'data_hora_ultima_movimentacao' => Carbon::now('America/Sao_Paulo'),
                ]); 
            }
            $this->selectedCards = [];
        }
        return;
    }

    public function prepareCards()
    {
        foreach ($this->selectedCards as $key => $value) {
            $this->cardsArray[$key] = $value;
        }
        $selection = array_filter($this->cardsArray,  function($value) { return !(is_null($value) || $value === false); });
        
        return $selection;
    }

    public function fieldsValidation()
    {
        $this->validate(
            [
                'startDate' => 'required|after_or_equal:01/01/2000',
                'endDate' => 'required|date|after_or_equal:startDate',
                'selectedCards' => 'required|array|min:1',
                'selectedCards.*' => 'required'
            ],
            [
                'startDate.required' => 'Data de início da sprint é obrigatória',
                'endDate.required' => 'Data de fim da sprint é obrigatória',
                'startDate.after_or_equal' => 'Insira uma data válida',
                'endDate.after_or_equal' => 'Insira uma data válida',
                'selectedCards.required' => 'Selecione ao menos uma tarefa',
            ],
        );

        return true;
    }

    public function countSquadSprints()
    {
        $sprintNumberQuery = <<<SQL
            SELECT COUNT(sp.numero) AS quantidade_sprint
            FROM sprint sp
            JOIN squad sq
            ON sp.squad_id = sq.id
            WHERE sq.id = ?
        SQL;

        return (array) DB::selectOne(
            $sprintNumberQuery,
            [$this->sessionParams['squad_id']],
        );
    }

    public function getStartColumn()
    {
        $todoQuery = <<<SQL
            SELECT
                c.id
            FROM coluna c
            JOIN quadro_kanban qk
                ON c.quadro_kanban_id = qk.id
            JOIN squad s
                ON s.id = qk.squad_id
            WHERE s.id = ?
            AND c.ordem = 1
            AND c.inicio_tarefa = 1;
        SQL;

        return (array) DB::selectOne(
            $todoQuery,
            [$this->sessionParams['squad_id']]
        );
    }
    
    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        
        $teamQuery = <<<SQL
            SELECT
                e.id as equipe_id
                , s.id as squad_id
                , su.usuario_id
                , e.nome
                , p.permitido AS permissao_gerenciar_backlog
                , c.referencia AS cargo
            FROM equipe e
            JOIN squad s
                ON e.id = s.equipe_id
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN squad_usuario su
                ON s.id = su.squad_id
                AND eu.usuario_id = su.usuario_id
            JOIN cargo c
                ON su.cargo_id = c.id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE e.id = ?
                AND s.id = ?
                AND su.usuario_id = ?
                AND tp.referencia = "[BACKLOG] MNG_SQUAD_SPRINTS"
                AND p.permitido = 1
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $this->sessionParams['squad_id'],
                $this->sessionParams['usuario_id'],
            ],
        );
    }

    public function fetchKanbanData() {
        $dataQuery = <<<SQL
            SELECT
                qk.id AS id
                , s.nome as squad_nome
            FROM quadro_kanban qk
            JOIN squad_usuario su
                ON qk.squad_id = su.squad_id
            JOIN squad s
                ON su.squad_id = s.id
            WHERE su.usuario_id = ?
                AND su.squad_id = ?;
        SQL;

        return (array) DB::selectOne(
            $dataQuery,
            [$this->sessionParams['usuario_id'], $this->sessionParams['squad_id']],
        );
    }

    public function fetchColumn(int $quadroKanbanId) {
        $columnsQuery = <<<SQL
             SELECT
                c.id as id
                , c.nome as nome
                , c.descricao as descricao
                , c.inicio_tarefa
                , c.fim_tarefa
                , qk.squad_id
            FROM coluna c
            INNER JOIN quadro_kanban qk
                ON c.quadro_kanban_id = qk.id
            WHERE qk.id = ?
                AND qk.squad_id = ?
                AND c.ordem = 0
                AND c.inicio_tarefa = 1
            ORDER BY c.ordem ASC;
        SQL;

        return (array) DB::selectOne(
            $columnsQuery,
            [$quadroKanbanId, $this->sessionParams['squad_id']],
        );
    }

    private static function fetchColumnCards(int $columnId)
    {
        $cardsQuery = <<<SQL
            SELECT
                t.id AS card_id
                -- Pré-visualização
                , t.referencia
                , t.titulo
                , est.estimativa
                , est.forma
                , est.extensao
                , us1.id AS usuario_responsavel_id
                , us1.foto AS usuario_responsavel_foto
                , us1.nome AS usuario_responsavel_nome
                , us1.email AS usuario_responsavel_email
                , t.coluna_id AS tarefa_status
                -- Detalhamento da tarefa
                , t.detalhamento AS detalhamento 
                , t.data_hora_criacao
                , p.id
                , p.nome AS projeto_nome
                , t.projeto_id
                , t.data_hora_ultima_movimentacao
                , col.id AS id_coluna
                , col.nome AS nome_coluna
                , t.prioridade AS prioridade
                , us2.foto AS usuario_relator_foto
                , us2.nome AS usuario_relator_nome
            FROM tarefa t
            LEFT JOIN coluna col
                ON t.coluna_id = col.id
            LEFT JOIN usuario us1
                ON t.responsavel_id = us1.id
            INNER JOIN usuario us2
                ON t.relator_id = us2.id
            LEFT JOIN estimativa_tarefa est
                ON t.estimativa_tarefa_id = est.id
            LEFT JOIN projeto p
                ON t.projeto_id = p.id
            LEFT JOIN sprint sp
                ON sp.id = t.sprint_id
            WHERE col.id = ?
            AND t.sprint_id IS NULL
            ORDER BY t.prioridade ASC
        SQL;

        return (array) DB::select(
            $cardsQuery,
            [$columnId]
        );
    }


}

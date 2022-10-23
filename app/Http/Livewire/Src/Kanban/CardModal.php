<?php

namespace App\Http\Livewire\Src\Kanban;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CardModal extends Component
{
    public $data;
    public $cardMentions;
    public $squadMemberId;
    public $squadMembers;
    public $squadMember;
    public $titulo;
    public $detalhamento;
    public $taskOwnerId;
    public $estimativeRadio;
    public $spValue;
    public $timeValue;
    public $prioridade;
    public $statusSelecionado;
    public $columns;
    public $teamDataAndPermission;
    public $sessionParams;
    public $alias;


    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->cardMentions = self::fetchCardMentions((int) $this->data['id']);
        $this->squadMembers = self::fetchSquadMembers(session('user_data'));
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        return view('livewire.src.kanban.card-modal');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        
        $teamQuery = <<<SQL
            SELECT
                e.id AS equipe_id
                , s.id AS squad_id
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
            WHERE s.id = ?
                AND eu.usuario_id = ?
                AND tp.referencia = "[BACKLOG] MNG_SQUAD_SPRINTS"
                AND p.permitido = 1
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $sessionParams['squad_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }
    
    private static function fetchCardMentions(int $cardId)
    {
        $mentionsQuery = <<<SQL
            SELECT
                m.id
                -- Tarefas
                , m.tarefa_origem_id
                , t.referencia AS tarefa_referencia
                , t.titulo AS tarefa_titulo
                , t.prioridade
                , t.coluna_id as tarefa_status
                -- Documentações
                , m.documentacao_origem_id
                , d.referencia AS documentacao_referencia
                , d.titulo AS documentacao_titulo
            FROM mencao m
            LEFT JOIN tarefa t
                ON m.tarefa_origem_id = t.id
            LEFT JOIN documentacao d
                ON m.documentacao_origem_id = d.id
            WHERE tarefa_mencionada_id = ?
                AND (
                    m.tarefa_origem_id IS NOT NULL
                    OR m.documentacao_origem_id IS NOT NULL
                )
                AND (
                    d.excluida <> 1
                    OR d.excluida IS NULL
                )
        SQL;

        $mentions = DB::select(
            $mentionsQuery,
            [$cardId]
        );

        $groupedMentions = [];

        foreach ($mentions as $mention) {
            if ($mention->tarefa_origem_id) {
                $groupedMentions['tarefas'][] = $mention;

                continue;
            }

            $groupedMentions['documentacoes'][] = $mention;
        }

        return $groupedMentions;
    }

    private static function fetchSquadMembers(array $sessionParams)
    {
        $squadMembersQuery = <<<SQL
            SELECT
                u.id
                , u.nome
                , u.email
            FROM usuario u
            JOIN squad_usuario su
                ON u.id = su.usuario_id
                AND su.squad_id = ?
            ORDER BY u.nome ASC
        SQL;

        return (array) DB::select(
            $squadMembersQuery,
            [$sessionParams['squad_id']],
        );
    }

    public function updateCard() 
    {
        $location = self::returnTo();
        $estimativaId = null;
        $updateOwner = $this->taskOwnerId;
        $updatePriority = $this->prioridade == null ? $this->data['prioridade'] : $this->prioridade;
        $updateColumn = $this->statusSelecionado == null || $this->statusSelecionado === "0"
            ? $this->data['id_coluna'] 
            : $this->statusSelecionado;

        if ($this->taskOwnerId === null) {
            $updateOwner = $this->data['usuario_responsavel_id'];
        }
        if( $this->taskOwnerId === "0") {
            $updateOwner = null;
        }


        if($this->estimativeRadio && $this->spValue || $this->timeValue !== null) {
            $estimativa= self::setEstimatives();
            $forma = $estimativa[1] == "Sp" ? $forma = "Fibonacci" : $forma = "Horas";
            
            $estimativaId = DB::table('estimativa_tarefa')->insertGetId([
                'estimativa' => $estimativa[0],
                'forma' => $forma,
                'extensao' => $this->estimativeRadio,
            ]);
        }


        DB::table('tarefa')
            ->where('id', $this->data['id'])
            ->update([
                'titulo' => ucfirst($this->titulo),
                'responsavel_id' => $updateOwner,
                'detalhamento' => $this->detalhamento,
                'data_hora_ultima_movimentacao' =>  Carbon::now('America/Sao_Paulo'),
                'prioridade' => $updatePriority,
                'estimativa_tarefa_id' => $estimativaId,
                'coluna_id' => $updateColumn,
            ]);

        return redirect()->to($location); 
    }

    public function returnTo()
    {
        $location;

        if($this->alias === "kanban") {
            return $location = $this->alias;
        }
        if($this->alias === "backlog") {
            $equipe = (int) $this->teamDataAndPermission["equipe_id"];
            $squad = (int) $this->sessionParams["squad_id"];

            return $location = $this->alias."/".$equipe."/".$squad;
        }
    }

    public function setEstimatives() 
    {
        if ($this->estimativeRadio == "Sp") {
            $this->timeValue = null;
            $estimative = [$this->spValue, $this->estimativeRadio];
        } else {
            $this->spValue = null;
            $estimative = [$this->timeValue, $this->estimativeRadio];
        }
        return $estimative;
    }
}

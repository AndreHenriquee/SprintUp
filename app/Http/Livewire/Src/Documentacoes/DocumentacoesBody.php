<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentacoesBody extends Component
{
    public $invalidSearchPattern = '/[^a-zA-ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ0-9-' . '\s' . ']/u';
    protected $listeners = ['validateRouteParams'];

    public $alias;
    public $routeParams;

    public $sessionParams, $teamDataAndPermission;
    public $taskMentions;
    public $memberMentions;

    public $textFilter;
    public $taskMentionIdFilter;
    public $memberMentionIdFilter;
    public $dateFilter;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);

        $this->taskMentions = self::fetchTaskMentions($this->sessionParams);
        $this->memberMentions = self::fetchMemberMentions($this->sessionParams);

        return view('livewire.src.documentacoes.documentacoes-body');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.id
                , p.permitido AS permissao_gerenciar_documentacoes
            FROM equipe e
            JOIN squad s
                ON e.id = s.equipe_id
            JOIN equipe_usuario eu
                ON e.id = eu.equipe_id
            JOIN permissao p
                ON eu.grupo_permissao_id = p.grupo_permissao_id
            JOIN tipo_permissao tp
                ON p.tipo_permissao_id = tp.id
            WHERE eu.usuario_id = ?
                AND s.id = ?
                AND tp.referencia = "[DOCS] MNG_DOCUMENTATIONS"
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $sessionParams['usuario_id'],
                $sessionParams['squad_id'],
            ],
        );
    }

    public function validateRouteParams()
    {
        $updateFilters = false;

        if (
            !in_array($this->routeParams['texto'], ['', 'null']) &&
            preg_match($this->invalidSearchPattern, $this->routeParams['texto'])
        ) {
            $this->textFilter = null;
            $updateFilters = true;
        }

        if (
            !in_array($this->routeParams['mencao_tarefa'], ['', 'null']) &&
            !in_array((int) $this->routeParams['mencao_tarefa'], array_column($this->taskMentions, 'id'))
        ) {
            $this->taskMentionIdFilter = null;
            $updateFilters = true;
        }

        if (
            !in_array($this->routeParams['mencao_membro'], ['', 'null']) &&
            !in_array((int) $this->routeParams['mencao_membro'], array_column($this->memberMentions, 'id'))
        ) {
            $this->memberMentionIdFilter = null;
            $updateFilters = true;
        }

        $date = \DateTime::createFromFormat('Y-m-d', $this->routeParams['data']);

        if (
            !in_array($this->routeParams['data'], ['', 'null']) &&
            !($date && $date->format('Y-m-d') === $this->routeParams['data'])
        ) {
            $this->dateFilter = null;
            $updateFilters = true;
        }

        if ($updateFilters) {
            self::updateFilters();
        }
    }

    public function updateFilters()
    {
        $route = '/' . $this->alias . '/';

        $this->textFilter = preg_replace($this->invalidSearchPattern, '', (string) $this->textFilter);

        $normalizedTextFilter = empty($this->textFilter) ? 'null' : $this->textFilter;
        $normalizedTaskMentionIdFilter = empty($this->taskMentionIdFilter) ? 'null' : $this->taskMentionIdFilter;
        $normalizedMemberMentionIdFilter = empty($this->memberMentionIdFilter) ? 'null' : $this->memberMentionIdFilter;
        $normalizedDateFilter = empty($this->dateFilter) ? 'null' : $this->dateFilter;

        $route .= implode('/', [
            $normalizedTextFilter,
            $normalizedTaskMentionIdFilter,
            $normalizedMemberMentionIdFilter,
            $normalizedDateFilter,
        ]);

        return redirect($route);
    }

    private static function fetchTaskMentions(array $sessionParams)
    {
        $taskMentionsQuery = <<<SQL
            SELECT
                id
                , referencia
                , titulo
            FROM tarefa
            WHERE squad_id = ?
            ORDER BY titulo ASC
        SQL;

        return (array) DB::select(
            $taskMentionsQuery,
            [$sessionParams['squad_id']],
        );
    }

    private static function fetchMemberMentions(array $sessionParams)
    {
        $taskMentionsQuery = <<<SQL
            SELECT
                u.id
                , u.email
                , u.nome
            FROM usuario u
            JOIN squad_usuario su
                ON u.id = su.usuario_id
                AND su.squad_id = ?
            ORDER BY u.nome ASC
        SQL;

        return (array) DB::select(
            $taskMentionsQuery,
            [$sessionParams['squad_id']],
        );
    }
}

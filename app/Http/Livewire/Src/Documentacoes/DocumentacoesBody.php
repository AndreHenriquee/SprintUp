<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentacoesBody extends Component
{
    public $invalidSearchPattern = '/[^a-zA-ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ0-9-' . '\s' . ']/u';
    public $listeners = ['validateRouteParams'];
    public $alias;
    public $routeParams;
    public $taskMentions;
    public $memberMentions;

    public $textFilter;
    public $taskMentionIdFilter;
    public $memberMentionIdFilter;
    public $dateFilter;

    public function render()
    {
        $this->taskMentions = self::fetchTaskMentions(session('user_data'));
        $this->memberMentions = self::fetchMemberMentions(session('user_data'));

        return view('livewire.src.documentacoes.documentacoes-body');
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

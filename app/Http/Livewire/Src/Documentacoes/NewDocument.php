<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewDocument extends Component
{
    public $routeParams;

    public $sessionParams, $teamDataAndPermission, $docTypes;

    public $docTitle, $docType, $docDate, $docContent, $isLimitedToSquad = false;
    public $minDate, $maxDate;

    public function render()
    {
        $this->sessionParams = session('user_data');
        $this->teamDataAndPermission = self::fetchTeamDataAndPermission($this->sessionParams);
        $this->docTypes = self::fetchDocTypes();

        $this->minDate = "1900-01-01";
        $this->maxDate = date('Y-m-d');

        return view('livewire.src.documentacoes.new-document');
    }

    private function fetchTeamDataAndPermission(array $sessionParams)
    {
        $teamQuery = <<<SQL
            SELECT
                e.nome
                , p.permitido AS permissao_gerenciar_documentacoes
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
                AND eu.usuario_id = ?
                AND tp.referencia = "[DOCS] MNG_DOCUMENTATIONS"
        SQL;

        return (array) DB::selectOne(
            $teamQuery,
            [
                $this->routeParams['equipe_id'],
                $sessionParams['squad_id'],
                $sessionParams['usuario_id'],
            ],
        );
    }

    private static function fetchDocTypes()
    {
        return [
            'INFORMATION' => 'Informações',
            'SPRINT_PLANNING' => 'Sprint Planning',
            'DAILY_SCRUM' => 'Daily Scrum',
            'SPRINT_REVIEW' => 'Sprint Review',
            'SPRINT_RETROSPECTIVE' => 'Sprint Retrospective',
        ];
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'docTitle' => 'required|min:3|max:100',
                'docType' => 'required|not_in:0',
                'docDate' => 'nullable|date|after_or_equal:minDate|before_or_equal:maxDate',
                'docContent' => 'nullable|min:10|max:50000',
            ],
            [
                'docTitle.required' => 'O título é obrigatório',
                'docTitle.min' => 'O título precisa ter no mínimo 3 caracteres',
                'docTitle.max' => 'O título pode ter no máximo 100 caracteres',
                'docDate.date' => 'Insira uma data válida',
                'docDate.after_or_equal' => 'Insira uma data realista',
                'docDate.before_or_equal' => 'A data não pode ser futura',
                'docContent.min' => 'O conteúdo precisa ter no mínimo 10 caracteres',
                'docContent.max' => 'O conteúdo pode ter no máximo 10 mil caracteres',
            ],
        );

        return true;
    }

    private function createRef(string $docTitle)
    {
        if (
            strpos($docTitle, ' ')
            || strpos($docTitle, ',')
            || strpos($docTitle, '_')
            || strpos($docTitle, '-')
        ) {
            $docTitle = preg_split("/[\s,_-]+/", $docTitle);

            $referencia = '';

            foreach ($docTitle as $letra) {
                $referencia .= mb_substr($letra, 0, 1);
            }

            $docRef = strtoupper($referencia);
        } else {
            $docRef = strtoupper($docTitle);
        }

        $numberOfDocsWithSameRefQuery = <<<SQL
            SELECT
                COUNT(id) AS numero_documentacoes
            FROM documentacao
            WHERE equipe_id = ?
                AND (
                    referencia = ?
                    OR referencia LIKE ?
                )
        SQL;

        $numberOfDocsWithSameRef = (int) DB::selectOne(
            $numberOfDocsWithSameRefQuery,
            [$this->routeParams['equipe_id'], $docRef, $docRef . '-%']
        )->numero_documentacoes;

        if (!$numberOfDocsWithSameRef) {
            return $docRef;
        }

        return $docRef . '-' . $numberOfDocsWithSameRef + 1;
    }

    public function registerDoc()
    {
        if ($this->dataValidated()) {
            DB::table('documentacao')->insert([
                'referencia' => self::createRef($this->docTitle),
                'titulo' => trim($this->docTitle),
                'conteudo' => trim($this->docContent),
                'tipo' => $this->docType,
                'data_hora' => empty($this->docDate) ? date('Y-m-d H:i:s') : $this->docDate . ' 00:00:00',
                'equipe_id' => $this->routeParams['equipe_id'],
                'squad_id' =>  $this->isLimitedToSquad ? $this->sessionParams['squad_id'] : null,
            ]);

            return redirect('/documentacoes');
        }
    }
}

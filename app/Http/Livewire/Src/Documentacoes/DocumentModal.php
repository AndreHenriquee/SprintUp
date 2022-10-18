<?php

namespace App\Http\Livewire\Src\Documentacoes;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentModal extends Component
{
    public $listeners = [];
    public $sessionParams;

    public $teamDataAndPermission, $data, $typeMap;
    public $mentions;
    public $taskList, $memberList, $docList;

    public $docTitle, $docContent;
    public $commentList, $taskMentionId, $memberMentionId, $docMentionId;
    public $docComment, $loggedUserComments;

    public function render()
    {
        $this->listeners = ['excludeDoc-' . $this->data['id'] => 'excludeDoc'];
        $this->sessionParams = session('user_data');

        $this->commentList = self::fetchCommentList((int) $this->data['id']);
        $this->taskList = self::fetchTaskList($this->sessionParams);
        $this->memberList = self::fetchMemberList($this->sessionParams);
        $this->docList = self::fetchDocList($this->sessionParams);

        $this->mentions = self::fetchMentions((int) $this->data['id']);

        return view('livewire.src.documentacoes.document-modal');
    }

    private static function fetchCommentList(int $docId)
    {
        $commentListQuery = <<<SQL
            SELECT
                c.id
                , c.texto
                , c.data_hora
                , u.id AS usuario_id
                , u.nome AS usuario_nome
                , u.email AS usuario_email
            FROM comentario c
            JOIN usuario u
                ON c.usuario_id = u.id
            WHERE c.documentacao_id = ?
            ORDER BY data_hora DESC
        SQL;

        return (array) DB::select(
            $commentListQuery,
            [$docId],
        );
    }

    private static function fetchTaskList(array $sessionParams)
    {
        $taskListQuery = <<<SQL
            SELECT
                id
                , referencia
                , titulo
            FROM tarefa
            WHERE squad_id = ?
            ORDER BY titulo ASC
        SQL;

        return (array) DB::select(
            $taskListQuery,
            [$sessionParams['squad_id']],
        );
    }

    private static function fetchMemberList(array $sessionParams)
    {
        $memberListQuery = <<<SQL
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
            $memberListQuery,
            [$sessionParams['squad_id']],
        );
    }

    private static function fetchDocList(array $sessionParams)
    {
        $docListQuery = <<<SQL
            SELECT
                d.id
                , d.referencia
                , d.titulo
            FROM documentacao d
            JOIN squad s
                ON s.id = ?
            WHERE (
                d.squad_id = s.id
                OR (
                    d.squad_id IS NULL
                    AND d.equipe_id = s.equipe_id
                )
            )
                AND (
                    s.excluida <> 1
                    OR s.excluida IS NULL
                )
                AND (
                    d.excluida <> 1
                    OR d.excluida IS NULL
                )
            GROUP BY d.id, d.referencia, d.titulo, d.data_hora, d.tipo, d.conteudo
            ORDER BY d.titulo ASC
        SQL;

        return (array) DB::select(
            $docListQuery,
            [$sessionParams['squad_id']],
        );
    }

    private static function fetchMentions(int $docId)
    {
        $taskMentionsQuery = <<<SQL
            SELECT
                m.id
                , m.tarefa_mencionada_id
                , t.referencia AS tarefa_referencia
                , t.titulo AS tarefa_titulo
                , c.nome as tarefa_status
            FROM mencao m
            JOIN tarefa t
                ON m.tarefa_mencionada_id = t.id
            JOIN coluna c
                ON t.coluna_id = c.id
            WHERE m.documentacao_origem_id = ?
        SQL;

        $taskMentions = (array) DB::select(
            $taskMentionsQuery,
            [$docId]
        );

        $memberMentionsQuery = <<<SQL
            SELECT
                m.id
                , m.usuario_mencionado_id
                , u.nome AS usuario_nome
                , u.email AS usuario_email
            FROM mencao m
            JOIN usuario u
                ON m.usuario_mencionado_id = u.id
            WHERE m.documentacao_origem_id = ?
        SQL;

        $memberMentions = (array) DB::select(
            $memberMentionsQuery,
            [$docId]
        );

        $docMentionsQuery = <<<SQL
            SELECT
                m.id
                , m.documentacao_mencionada_id
                , d.referencia AS documentacao_referencia
                , d.titulo AS documentacao_titulo
                , d.tipo AS documentacao_tipo
            FROM mencao m
            JOIN documentacao d
                ON m.documentacao_mencionada_id = d.id
            WHERE m.documentacao_origem_id = ?
                AND (
                    d.excluida <> 1
                    OR d.excluida IS NULL
                )
        SQL;

        $docMentions = (array) DB::select(
            $docMentionsQuery,
            [$docId]
        );

        return [
            'tarefas' => $taskMentions,
            'usuarios' => $memberMentions,
            'documentacoes' => $docMentions,
        ];
    }

    private function dataValidated()
    {
        $this->validate(
            [
                'docTitle' => 'required|min:3|max:100',
                'docContent' => 'nullable|min:10|max:50000',
            ],
            [
                'docTitle.required' => 'O título é obrigatório',
                'docTitle.min' => 'O título precisa ter no mínimo 3 caracteres',
                'docTitle.max' => 'O título pode ter no máximo 100 caracteres',
                'docContent.min' => 'O conteúdo precisa ter no mínimo 10 caracteres',
                'docContent.max' => 'O conteúdo pode ter no máximo 10 mil caracteres',
            ],
        );

        return true;
    }

    private function doesDataChanged()
    {
        if (
            trim($this->data['titulo']) <> trim($this->docTitle)
            || trim($this->data['conteudo']) <> trim($this->docContent)
        ) {
            return true;
        }

        return false;
    }

    public function excludeDoc()
    {
        DB::table('documentacao')
            ->where('id', (int) $this->data['id'])
            ->update(['excluida' => 1]);

        return redirect('/documentacoes');
    }

    public function addTaskMention()
    {
        if (empty($this->taskMentionId) || $this->taskMentionId == 'null') {
            $this->emit(
                'noMentionSelected-' . $this->data['id'],
                'Você não selecionou nenhuma tarefa para mencionar.'
            );

            return;
        }

        DB::table('mencao')
            ->insert([
                'documentacao_origem_id' => (int) $this->data['id'],
                'tarefa_mencionada_id' => (int) $this->taskMentionId,
            ]);

        $this->taskMentionId = null;
        $this->emit('addedTaskMention-' . $this->data['id']);
    }

    public function addMemberMention()
    {
        if (empty($this->memberMentionId) || $this->memberMentionId == 'null') {
            $this->emit(
                'noMentionSelected-' . $this->data['id'],
                'Você não selecionou nenhum membro da equipe para mencionar.'
            );

            return;
        }

        DB::table('mencao')
            ->insert([
                'documentacao_origem_id' => (int) $this->data['id'],
                'usuario_mencionado_id' => (int) $this->memberMentionId,
            ]);

        $this->memberMentionId = null;
        $this->emit('addedMemberMention-' . $this->data['id']);
    }

    public function addDocMention()
    {
        if (empty($this->docMentionId) || $this->docMentionId == 'null') {
            $this->emit(
                'noMentionSelected-' . $this->data['id'],
                'Você não selecionou nenhuma documentação para mencionar.'
            );

            return;
        }

        DB::table('mencao')
            ->insert([
                'documentacao_origem_id' => (int) $this->data['id'],
                'documentacao_mencionada_id' => (int) $this->docMentionId,
            ]);

        $this->docMentionId = null;
        $this->emit('addedDocMention-' . $this->data['id']);
    }

    public function removeMention(int $mentionId)
    {
        DB::table('mencao')->where('id', $mentionId)->delete();
    }

    private function validateComment(string $newCommentText, string $oldCommentText)
    {
        $eventName = 'invalidComment-' . $this->data['id'];

        if (!empty($oldCommentText) && $newCommentText == $oldCommentText) {
            $this->emit($eventName, 'Nada foi alterado neste comentário!');
            return false;
        }

        $commentLength = strlen(utf8_decode($newCommentText));

        if ($commentLength < 3 || $commentLength > 500) {
            $this->emit($eventName, 'O comentário deve ter entre 3 a 500 caracteres!');
            return false;
        }

        return true;
    }

    public function addComment()
    {
        $comment = trim($this->docComment);

        if ($this->validateComment($comment, '')) {
            $newCommentId = (int) DB::table('comentario')
                ->insertGetId([
                    'texto' => $comment,
                    'data_hora' => date('Y-m-d H:i:s'),
                    'usuario_id' => (int) $this->sessionParams['usuario_id'],
                    'documentacao_id' => (int) $this->data['id'],
                ]);

            $this->emit(
                'registeredComment-' . $this->data['id'],
                $newCommentId,
                true,
                $comment
            );
        }
    }

    public function updateComment(int $commentId, string $oldComment)
    {
        $comment = trim($this->loggedUserComments[$commentId]);

        if ($this->validateComment($comment, trim($oldComment))) {
            DB::table('comentario')
                ->where('id', $commentId)
                ->update([
                    'texto' => $comment,
                    'data_hora' => date('Y-m-d H:i:s'),
                ]);

            $this->emit(
                'registeredComment-' . $this->data['id'],
                $commentId,
                false,
                $comment
            );
        }
    }

    public function saveChanges()
    {
        if ($this->dataValidated()) {
            if ($this->doesDataChanged()) {
                DB::table('documentacao')
                    ->where('id', (int) $this->data['id'])
                    ->update([
                        'titulo' => trim($this->docTitle),
                        'conteudo' => trim($this->docContent),
                    ]);

                return redirect('/documentacoes');
            }

            $this->emit('noDataChanged-' . $this->data['id']);
        }
    }
}

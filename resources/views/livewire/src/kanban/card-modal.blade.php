<div class="modal fade" id="modalCard-{{$data['id']}}" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <label style="width:auto" class="modal-title">{{$data['referencia']}}</label>
                <input type="text" id="title-{{$data['id']}}" wire:model="titulo" class="form-control" style="background-color:rgba(240, 240, 240, 0.35);border:none;font-size:17px;width:950px;margin-left:10px;" value="">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h5 class="modal-title mb-2">Descrição</h5>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows="30" spellcheck="false" wire:model="detalhamento" id="detalhamentoBody-{{$data['id']}}" style="background-color:rgba(230, 230, 230, 0.35);border:none;resize:none;max-height:900px"></textarea>
                    </div>
                    <div class="col-3 bg-light overflow-auto">
                        <p class="text-wrap mt-3"><b>Relator:</b> {{$data['usuario_relator_nome']}}</p>
                        <p class="text-wrap"><b>Responsável:</b></p>
                        <input class="form-control mb-2" autocomplete="off" list="squadMembers-{{$data['id']}}" id="selectedTaskMember-{{$data['id']}}" value="{{ $data['usuario_responsavel_nome'] && $data['usuario_responsavel_email'] ? $data['usuario_responsavel_nome'].' ('.$data['usuario_responsavel_email'].')' : 'Nenhum' }}" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                        <datalist id="squadMembers-{{$data['id']}}">
                            @foreach($squadMembers as $squadMember)
                            <option data-value="{{$squadMember->id}}">
                                {{$squadMember->nome}} ({{$squadMember->email}})
                            </option>
                            @endforeach
                        </datalist>
                        <input class="d-none" type="text" wire:model="taskOwnerId" id="selectedTaskMember-{{$data['id']}}-hidden">

                        <p class="text-wrap mt-3"><b>Status:</b></p>

                        <select class="form-select mb-2" id="tarefaStatus-{{$data['id']}}" wire:model="statusSelecionado" style="background-color:rgba(195, 195, 195, 0.35);border:none;">
                            <option selected value="0">Escolher um status</option>    
                        @foreach($columns as $column)
                            <?php
                            $isCurrentColumn = (int) $data['id_coluna'] == (int) ((array) $column)['id'];
                            ?>
                            <option <?= $isCurrentColumn ? 'disabled selected' : '' ?> value="<?= ((array) $column)['id'] ?>">
                                <?= ((array) $column)['nome'] ?> <?= $isCurrentColumn ? ' (Coluna atual)' : '' ?>
                            </option>
                            @endforeach
                        </select>
                        <p class="text-wrap mt-3" id="prioridade-{{$data['id']}}" wire:model="prioridade"> <b>Prioridade:</b> {{$data['prioridade']}}</p>
                        @if($teamDataAndPermission['cargo'] != "ST")
                            <input class="form-control mb-3" wire:model="prioridade" id="prioridade-{{$data['id']}}" value="{{$data['prioridade']}}" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                        @endif

                        @if($data['estimativa'])
                        <p><b>Estimativa:</b> {{$data['estimativa']}}{{$data['extensao']}} @else <p><b>Estimativa:</b> Nenhuma
                        @endif

                        @if($teamDataAndPermission['cargo'] != "ST")
                            <div class="row">
                                <div class="col">
                                    <input class="form-check-input" wire:model="estimativeRadio" name="estimativeRadio" id="hoursRadio" type="radio" value="H">
                                    <label class="form-check-label">
                                        Horas
                                    </label>
                                </div>

                                <div class="col">
                                    <input class="form-check-input" wire:model="estimativeRadio" name="estimativeRadio" id="storyPointsRadio" type="radio" value="Sp">
                                    <label class="form-check-label">
                                        Story Points
                                    </label>
                                </div>
                            </div>
                            @if($estimativeRadio == "Sp")
                                <div style="" id="storyPoints">
                                    <p class="form-label mt-3">
                                        <b>Story Points: </b> 
                                    </p>
                                    <select class="form-select" wire:model="spValue" id="selectPointsEstimative" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                                        <option value="" selected>Selecione a estimativa</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="5">5</option>
                                        <option value="8">8</option>
                                        <option value="13">13</option>
                                    </select>
                                </div>
                            @endif
                            @if($estimativeRadio == "H")
                            <div style="" id="hours">
                                <p class="form-label mt-3"><b>Horas: </b> </p>
                                <input type="number" id="inputId" class="form-control mb-2" wire:model="timeValue" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                            </div>
                            @endif
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    @if (isset($cardMentions['tarefas']))
                                    <p class="text-wrap"><b>Menções de tarefas:</b></p>
                                    @foreach($cardMentions['tarefas'] as $taskMentions)
                                    <a href="/kanban" class="d-inline-block mb-1 me-1 bg-secondary text-light h6 p-1 rounded" style="cursor:pointer; text-decoration: none;" title="{{$taskMentions->tarefa_referencia}} | {{$taskMentions->tarefa_titulo}}">
                                        {{$taskMentions->tarefa_referencia}}
                                    </a>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="mt-2">
                                    @if (isset($cardMentions['documentacoes']))
                                    <p class="text-wrap"><b>Menções de documentações:</b></p>
                                    @foreach($cardMentions['documentacoes'] as $documentMentions)
                                    <a href="/documentacoes/{{$documentMentions->documentacao_referencia}}/null/null/null" class="d-inline-block mb-1 me-1 bg-secondary text-light h6 p-1 rounded" style="cursor:pointer; text-decoration: none;" title="{{$documentMentions->documentacao_referencia}} | {{$documentMentions->documentacao_titulo}}">
                                        {{$documentMentions->documentacao_referencia}}
                                    </a>
                                    @endforeach
                                    @endif
                                </div>
                                <hr/>

                                <div class="row mt-3">
                                    @if($data['numero']) <p class="text-wrap" style="font-size:12px"><b>Sprint Numero:</b> {{$data['numero']}}</p> @endif
                                    @if($data['inicio'] && $data['fim'])
                                        <p class="text-wrap" style="font-size:13px"><b>Inicio da Sprint:</b> {{date_format(date_create($data['inicio']),"d/m/Y H:i:s")}}</p>
                                        <p class="text-wrap" style="font-size:13px"><b>Fim da Sprint:</b> {{date_format(date_create($data['fim']),"d/m/Y H:i:s")}}</p>
                                    @endif
                                    <p class="text-wrap" style="font-size:13px"><b>Criado em:</b> {{date_format(date_create($data['data_hora_criacao']),"d/m/Y H:i:s")}}</p>
                                    <p class="text-wrap" style="font-size:13px"><b>Atualizado em:</b> {{date_format(date_create($data['data_hora_ultima_movimentacao']),"d/m/Y H:i:s")}}</p>
                                </div>
                               
                                <div class="d-flex flex-row-reverse">
                                    <button wire:click="updateCard" class="col-2 btn btn-dark mt-3 mb-3" style="width:100%">Editar Card</button>
                                </div>
                                @if($teamDataAndPermission['cargo'] != "ST")
                                <div class="d-flex flex-row-reverse">
                                    <button id="deleteCard" wire:model="$sureToDelete" class="col-2 btn btn-danger mb-3" onclick="confirmation({{$data['id']}})" style="width:100%">Remover Card</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <h5 class="modal-title mb-2 mt-2">Comentários</h5>
                    <div class="col-12 mb-3">
                        <div class="input-group">
                            <textarea wire:model="taskComment" id="newCommentInput-{{$data['id']}}" class="form-control" style="resize: none;" placeholder="Escreva um novo comentário"></textarea>
                            <button wire:click="addComment" class="btn btn-outline-secondary">Comentar</button>
                        </div>
                    </div>
                    @if(!empty($commentList))
                    <div class="col-12 px-4">
                        @foreach($commentList as $comment)
                        <?php
                            $comment = (array) $comment;
                            $isCommentFromLoggedUser = (int) $sessionParams['usuario_id'] == (int) $comment['usuario_id'];
                        ?>
                        <div class="row mb-2 p-2 rounded border">
                            <div class="col-2 border-end border-secondary">
                                <p class="fw-bold">{{$comment['usuario_nome']}}</p>
                                <p class="fs-6">{{date_format(date_create($comment['data_hora']),"d/m/Y H:i:s")}}</p>
                            </div>
                            <div class="col-10">
                                @if($isCommentFromLoggedUser)
                                <textarea wire:model="loggedUserComments.{{$comment['id']}}" id="loggedUserComment-{{$comment['id']}}" class="form-control" style="resize: none;" placeholder="Escreva um novo comentário"></textarea>
                                <button wire:click="updateComment({{(int) $comment['id']}}, `{{$comment['texto']}}`)" class="btn btn-outline-secondary mt-3">Atualizar comentário</button>
                                @else
                                <textarea disabled id="otherUserComment-{{$comment['id']}}" class="form-control" style="resize: none;" placeholder="Atualize este comentário"></textarea>
                                @endif
                            </div>
                            <script>
                                document.addEventListener('livewire:load', function() {
                                    var commentText = `{{$comment['texto']}}`;

                                    var comment = document.getElementById("{{$isCommentFromLoggedUser ? 'loggedUserComment' : 'otherUserComment'}}-{{$comment['id']}}");
                                    comment.value = commentText;
                                    comment.dispatchEvent(new Event('input'));
                                });
                            </script>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        [...document.querySelectorAll('input[list]')].forEach(datalist => {
            datalist.addEventListener('input', function(e) {
                var input = e.target,
                    list = input.getAttribute('list'),
                    options = document.querySelectorAll('#' + list + ' option'),
                    hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
                    inputValue = input.value;

                hiddenInput.value = '0';
        
                for (var i = 0; i < options.length; i++) {
                    var option = options[i];
                    if (option.innerText.trim() === inputValue.trim()) {
                        hiddenInput.value = option.getAttribute('data-value');
                        break;
                    }
                }
                hiddenInput.dispatchEvent(new Event('input'));
            })
        });

        function confirmation(id) {
                if (confirm("Tem certeza que deseja deletar este card?")) {
                    Livewire.emit('removeCard', id)
                }
                else { alert("Card não deletado!") }
            }

        document.addEventListener('livewire:load', function() {
            Livewire.on("registeredComment-{{$data['id']}}", (commentId, isNewComment, commentText) => {
                var comment = document.getElementById("loggedUserComment-" + commentId);
                comment.value = commentText;
                comment.dispatchEvent(new Event('input'));

                if (isNewComment) {
                    var commentTextArea = document.getElementById("newCommentInput-{{$data['id']}}");
                    commentTextArea.value = null;
                    commentTextArea.dispatchEvent(new Event('input'));
                }

                alert('O comentário foi ' + (commentId ? 'atualizado' : 'adicionado') + ' com sucesso!');
            });

            var inputedText = `{{$data['detalhamento']}}`;
            var detalhamentoBody = document.getElementById("detalhamentoBody-{{$data['id']}}");
            detalhamentoBody.value = inputedText;
            detalhamentoBody.dispatchEvent(new Event('input'));

            var priority = "{{$data['prioridade']}}";
            var priorityInput = document.getElementById("prioridade-{{$data['id']}}");
            priorityInput.value = priority;
            priorityInput.dispatchEvent(new Event('input'));

            var taskTitle = `{{$data['titulo']}}`;
            var titleInput = document.getElementById("title-{{$data['id']}}");
            titleInput.value = taskTitle;
            titleInput.dispatchEvent(new Event('input'));
        });
    </script>
</div>
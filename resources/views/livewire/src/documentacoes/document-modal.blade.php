<div wire:ignore.self class="modal fade" id="modalDocument-{{$data['id']}}" tabindex="-1">
    <?php
    $allowedToManageDocs = $teamDataAndPermission['permissao_gerenciar_documentacoes']
        && ($data['tipo'] == 'INFORMATION'
            || in_array($teamDataAndPermission['cargo'], ['PO', 'SM'])
        );
    ?>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="row">
                    <div title="{{$data['referencia']}}" class="col-1 h5 modal-title fw-bold text-truncate">
                        {{$data['referencia']}}
                    </div>
                    <div class="col-7">
                        <input <?= $allowedToManageDocs ? '' : 'disabled' ?> wire:model="docTitle" class="modal-title h5 m-0 w-100 border-0 border-bottom" id="docTitle-{{$data['id']}}" type="text" autocomplete="off" placeholder="Título">
                        @error('docTitle') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-3 text-end">
                        {{date_format(date_create($data['data_hora']),"d/m/Y H:i:s")}}
                    </div>
                    <div class='col-1 text-end'>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-9 border-end">
                        <h5 class="modal-title mb-2">{{$typeMap[$data['tipo']]['titulo']}}</h5>
                        <textarea <?= $allowedToManageDocs ? '' : 'disabled' ?> wire:model="docContent" id="docContent-{{$data['id']}}" class="form-control bg-white" rows="15" autocomplete="off" placeholder="Conteúdo da documentação"></textarea>
                        @error('docContent') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-3 overflow-auto" style="max-height: 400px;">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-wrap"><b>Menções a tarefas:</b></p>
                                @if (!empty($mentions['tarefas']))
                                @foreach($mentions['tarefas'] as $taskMention)
                                <span class="d-inline-block mb-1 me-1 bg-secondary text-light h6 p-1 rounded">
                                    <a href="/kanban" class="pe-1 text-light" style="cursor:pointer; text-decoration: none;" title="{{$taskMention->tarefa_referencia}} | {{$taskMention->tarefa_titulo}} ({{$taskMention->tarefa_status}})">
                                        {{$taskMention->tarefa_referencia}}
                                    </a>
                                    <a wire:click="removeMention({{(int) $taskMention->id}})" class="ps-1 border-start text-light" style="cursor:pointer; text-decoration: none;" title="Remover esta menção">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="0.8rem" height="0.8rem" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                        </svg>
                                    </a>
                                </span>
                                @endforeach
                                @endif
                            </div>
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <input autocomplete="off" class="form-control" list="mentionedTasks-{{$data['id']}}" id="taskMentionSelect-{{$data['id']}}" placeholder="Tarefa a mencionar">
                                            <datalist id="mentionedTasks-{{$data['id']}}">
                                                @foreach($taskList as $task)
                                                @if(array_search($task->id, array_column($mentions['tarefas'], 'tarefa_mencionada_id')) === false)
                                                <option data-value="{{$task->id}}">
                                                    [{{$task->referencia}}] {{$task->titulo}}
                                                </option>
                                                @endif
                                                @endforeach
                                            </datalist>
                                            <input class="d-none" type="text" wire:model="taskMentionId" id="taskMentionSelect-{{$data['id']}}-hidden">
                                            <button wire:click="addTaskMention" title="Adicionar menção a tarefa" class="btn btn-outline-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-12">
                                <p class="text-wrap"><b>Menções a membros da equipe:</b></p>
                                @if (!empty($mentions['usuarios']))
                                @foreach($mentions['usuarios'] as $memberMention)
                                <span class="d-inline-block mb-1 me-1 bg-secondary text-light h6 p-1 rounded">
                                    <a href="/membros-equipe/{{$teamDataAndPermission['id']}}" class="pe-1 text-light" style="cursor:pointer; text-decoration: none;" title="{{$memberMention->usuario_nome}} | {{$memberMention->usuario_email}}">
                                        {{$memberMention->usuario_email}}
                                    </a>
                                    <a wire:click="removeMention({{(int) $memberMention->id}})" class="ps-1 border-start text-light" style="cursor:pointer; text-decoration: none;" title="Remover esta menção">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="0.8rem" height="0.8rem" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                        </svg>
                                    </a>
                                </span>
                                @endforeach
                                @endif
                            </div>
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <input autocomplete="off" class="form-control" list="mentionedMembers-{{$data['id']}}" id="memberMentionSelect-{{$data['id']}}" placeholder="Membro a mencionar">
                                            <datalist id="mentionedMembers-{{$data['id']}}">
                                                @foreach($memberList as $member)
                                                @if(array_search($member->id, array_column($mentions['usuarios'], 'usuario_mencionado_id')) === false)
                                                <option data-value="{{$member->id}}">
                                                    {{$member->nome}} ({{$member->email}})
                                                </option>
                                                @endif
                                                @endforeach
                                            </datalist>
                                            <input class="d-none" type="text" wire:model="memberMentionId" id="memberMentionSelect-{{$data['id']}}-hidden">
                                            <button wire:click="addMemberMention" title="Adicionar menção a membro" class="btn btn-outline-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-12">
                                <p class="text-wrap"><b>Menções a documentações:</b></p>
                                @if (!empty($mentions['documentacoes']))
                                @foreach($mentions['documentacoes'] as $docMention)
                                <span class="d-inline-block mb-1 me-1 bg-secondary text-light h6 p-1 rounded">
                                    <a href="/documentacoes/{{$docMention->documentacao_referencia}}/null/null/null" class="pe-1 text-light" style="cursor:pointer; text-decoration: none;" title="{{$docMention->documentacao_referencia}} | {{$docMention->documentacao_titulo}} ({{$docMention->documentacao_tipo}})">
                                        {{$docMention->documentacao_referencia}}
                                    </a>
                                    <a wire:click="removeMention({{(int) $docMention->id}})" class="ps-1 border-start text-light" style="cursor:pointer; text-decoration: none;" title="Remover esta menção">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="0.8rem" height="0.8rem" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                        </svg>
                                    </a>
                                </span>
                                @endforeach
                                @endif
                            </div>
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <input autocomplete="off" class="form-control" list="docMembers-{{$data['id']}}" id="docMentionSelect-{{$data['id']}}" placeholder="Documentação a mencionar">
                                            <datalist id="docMembers-{{$data['id']}}">
                                                @foreach($docList as $doc)
                                                @if(array_search($doc->id, array_column($mentions['documentacoes'], 'documentacao_mencionada_id')) === false)
                                                <option data-value="{{$doc->id}}">
                                                    [{{$doc->referencia}}] ({{$doc->titulo}})
                                                </option>
                                                @endif
                                                @endforeach
                                            </datalist>
                                            <input class="d-none" type="text" wire:model="docMentionId" id="docMentionSelect-{{$data['id']}}-hidden">
                                            <button wire:click="addDocMention" title="Adicionar menção a documentação" class="btn btn-outline-secondary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <b class="modal-title">Comentários</b>
                </div> -->
            </div>
            @if($allowedToManageDocs)
            <div class="modal-footer">
                <button id="excludeDoc-{{$data['id']}}" class="btn btn-danger">Excluir documentação</button>
                <button wire:click="saveChanges" class="btn btn-primary">Salvar alterações</button>
            </div>
            @endif
            <script>
                [...document.querySelectorAll('input[list]')].forEach(datalist => {
                    datalist.addEventListener('input', function(e) {
                        var input = e.target,
                            list = input.getAttribute('list'),
                            options = document.querySelectorAll('#' + list + ' option'),
                            hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
                            inputValue = input.value;

                        hiddenInput.value = 'null';

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

                document.addEventListener('livewire:load', function() {
                    var inputedDocTitle = "{{$data['titulo']}}";
                    var inputedDocContent = `{{$data['conteudo']}}`;

                    var docTitle = document.getElementById("docTitle-{{$data['id']}}");
                    docTitle.value = inputedDocTitle;
                    docTitle.dispatchEvent(new Event('input'));

                    var docContent = document.getElementById("docContent-{{$data['id']}}");
                    docContent.value = inputedDocContent;
                    docContent.dispatchEvent(new Event('input'));

                    Livewire.on("noDataChanged-{{$data['id']}}", () => {
                        alert('Nada foi alterado nesta documentação!');
                    })

                    Livewire.on("noMentionSelected-{{$data['id']}}", message => {
                        alert(message);
                    })

                    Livewire.on("addedTaskMention-{{$data['id']}}", () => {
                        document.getElementById("taskMentionSelect-{{$data['id']}}").value = null;

                        docContent.dispatchEvent(new Event('input'));
                    });

                    Livewire.on("addedMemberMention-{{$data['id']}}", () => {
                        document.getElementById("memberMentionSelect-{{$data['id']}}").value = null;

                        docContent.dispatchEvent(new Event('input'));
                    });

                    Livewire.on("addedDocMention-{{$data['id']}}", () => {
                        document.getElementById("docMentionSelect-{{$data['id']}}").value = null;

                        docContent.dispatchEvent(new Event('input'));
                    });

                    document.getElementById("excludeDoc-{{$data['id']}}").addEventListener('click', function() {
                        if (confirm('Você realmente quer excluir esta documentação?\n\nEsta ação é irreversível! Pense bem antes de confirmar.') == true) {
                            Livewire.emit("excludeDoc-{{$data['id']}}");
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>
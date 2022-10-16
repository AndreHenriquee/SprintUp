<div class="modal fade" id="modalCard-{{$data['id']}}" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <label style="width:auto" class="modal-title">{{$data['referencia']}}</label>
                <input type="text" id="title-{{$data['id']}}" class="form-control" wire:model="titulo" style="background-color:rgba(240, 240, 240, 0.35);border:none;font-size:15px;width:950px;margin-left:10px;" value="">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <b class="modal-title">Descrição</b>
                    <div class="col-sm-9 p-3">
                        <textarea class="form-control p-3" rows="20" spellcheck="false" wire:model="detalhamento" id="detalhamentoBody-{{$data['id']}}" style="background-color:rgba(240, 240, 240, 0.35);border:none;resize:none;"></textarea>
                    </div>
                    <div class="col-3 mt-3 bg-light">
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

                        <select class="form-control mb-2" id="tarefaStatus-{{$data['id']}}" wire:model="statusSelecionado" style="background-color:rgba(195, 195, 195, 0.35);border:none;">
                            @foreach($columns as $column)
                            <?php
                            $isCurrentColumn = (int) $data['id_coluna'] == (int) ((array) $column)['id'];
                            ?>
                            <option <?= $isCurrentColumn ? 'disabled' : '' ?> value="<?= ((array) $column)['id'] ?>">
                                <?= ((array) $column)['nome'] ?> <?= $isCurrentColumn ? ' (Coluna atual)' : '' ?>
                            </option>
                            @endforeach
                        </select>


                        <p class="text-wrap mt-3"><b>Prioridade:</b> </p>
                        <input class="form-control mb-3" wire:model="prioridade" id="prioridade-{{$data['id']}}" value="{{$data['prioridade']}}" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                        @if($data['estimativa'])
                        <p><b>Estimativa:</b> {{$data['estimativa']}}{{$data['extensao']}} @else
                        <p><b>Estimativa:</b> Nenhuma
                            @endif
                        <div class="row">
                            <div class="col">
                                <input class="form-check-input" wire:model="estimativeRadio" name="estimativeRadio" id="hoursRadio" type="radio" value="H">
                                <label class="form-check-label" type="radio">
                                    Horas
                                </label>
                            </div>

                            <div class="col">
                                <input class="form-check-input" wire:model="estimativeRadio" name="estimativeRadio" id="storyPointsRadio" type="radio" value="Sp">
                                <label class="form-check-label" type="radio">
                                    Story Points
                                </label>
                            </div>
                        </div>
                        @if($estimativeRadio == "Sp")
                        <div id="storyPoints">
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
                        <div id="hours">
                            <p class="form-label mt-3"><b>Horas: </b> </p>
                            <input type="number" id="inputId" class="form-control mb-2" wire:model="timeValue" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                        </div>
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
                                <div class="d-flex flex-row-reverse">
                                    <button wire:click="updateCard" class="col-2 btn btn-dark mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#modalCard" style="width:100%">Editar Card</button>
                                </div>
                                <p class="text-wrap" style="font-size:12px"><b>Criado em:</b> {{date_format(date_create($data['data_hora_criacao']),"d/m/Y H:i:s")}}</p>
                                <p class="text-wrap" style="font-size:12px"><b>Atualizado em:</b> {{date_format(date_create($data['data_hora_ultima_movimentacao']),"d/m/Y H:i:s")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <b class="modal-title">Comentários</b>
            </div> -->
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
        document.addEventListener('livewire:load', function() {
            var inputedText = `{{$data['detalhamento']}}`;
            var detalhamentoBody = document.getElementById("detalhamentoBody-{{$data['id']}}");
            detalhamentoBody.value = inputedText;
            detalhamentoBody.dispatchEvent(new Event('input'));

            var priority = `{{$data['prioridade']}}`;
            var priorityInput = document.getElementById("prioridade-{{$data['id']}}");
            priorityInput.value = priority;
            priorityInput.dispatchEvent(new Event('input'));

            var taskTitle = `{{$data['titulo']}}`;
            var titleInput = document.getElementById("title-{{$data['id']}}");
            titleInput.value = taskTitle;
            titleInput.dispatchEvent(new Event('input'));

            var ownerInput = document.getElementById("selectedTaskMember-{{$data['id']}}");
            ownerInput.dispatchEvent(new Event('select'));
        });
    </script>
</div>
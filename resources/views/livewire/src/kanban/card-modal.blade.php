<div class="modal fade" id="modalCard-{{$data['id']}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$data['referencia']}} | {{$data['titulo']}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <b class="modal-title">Descrição</b>
                    <div class="col-sm-9 p-3">
                        <!--<textarea class="form-control" >{{$data['detalhamento']}}</textarea>-->
                        <textarea class="form-control" rows="15" aria-label="With textarea" style="background-color:rgba(195, 195, 195, 0.35);border:none;">
                            {{$data['detalhamento']}}
                        </textarea>
                    </div>
                    <div class="col-3 mt-3 bg-light">
                        @if($data['usuario_responsavel_nome'])
                        <p><b>Responsável:</b> {{$data['usuario_responsavel_nome']}} @else <p><b>Responsável:</b> Nenhum
                        @endif
                        <p class="text-wrap"><b>Relator:</b> {{$data['usuario_relator_nome']}}</p>
                        <p class="text-wrap"><b>Prioridade:</b> {{$data['prioridade']}}</p>
                        <p class="text-wrap"><b>Status:</b> {{$data['nome_coluna']}}</p>
                        @if($data['estimativa'])
                        <p><b>Estimativa:</b> {{$data['estimativa']}} @else <p><b>Estimativa:</b> Nenhuma
                        @endif
                        <p class="text-wrap"><b>Criado em:</b> {{date_format(date_create($data['data_hora_criacao']),"d/m/Y H:i:s")}}</p>
                        <p class="text-wrap"><b>Atualizado em:</b> {{date_format(date_create($data['data_hora_ultima_movimentacao']),"d/m/Y H:i:s")}}</p>

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
</div>
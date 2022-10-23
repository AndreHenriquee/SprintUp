<div class="container">
    @if(isset($teamDataAndPermission['equipe_id']))
        @if($teamDataAndPermission['cargo'] != "ST")
            @if($cards != null)
                <div class="row">
                    <div class="h1 col-4 pe-3 border-end border-dark">
                        Nova sprint de {{$data['squad_nome']}}
                    </div>
                    <div class="col-8 my-auto">
                        Crie uma nova sprint para a squad
                    </div>
                </div>
                <hr class="opacity-100">
                <div class="row">
                    <div class="col-12">
                        <div class="row mb-3">
                            <div class="col-16 col-md-8">
                                <a class="btn btn-primary btn-dark" href="/backlog/{{$teamDataAndPermission['equipe_id']}}/{{$teamDataAndPermission['squad_id']}}">Voltar ao backlog</a>
                            </div>
                            <div class="col-6 col-md-4">
                                <div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto">
                        <label>Data de ínicio da sprint</label>
                        <input class="form-control" id="startDate" type="date" wire:model="startDate">
                        @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-auto">
                        <label>Data de fim da sprint</label>
                        <input class="form-control" id="startDate" type="date" wire:model="endDate">
                        @error('endDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-auto" style="margin-top:20px;margin-left:8px;padding:2px;width:300px">
                        <a class="btn btn-primary btn-primary" wire:click="addCards" >Criar Sprint</a>
                    </div>
                </div>
                <div class="row mb-2 rounded p-3 mt-3" style="background-color:#f2f2f2">
                    <div class="col-12">
                        <div class="h3">Tarefas em backlog</div>
                        @error('selectedCards') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="row mt-3">
                            <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
                                <thead>
                                    <tr>
                                        <th scope="col">Adicionar</th>
                                        <th scope="col">Referencia</th>
                                        <th scope="col">Título</th>
                                        <th scope="col">Prioridade</th>
                                        <th scope="col">Estimativa</th>
                                        <th scope="col">Criada em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($cards as $card)
                                <tr style="cursor:pointer">
                                    <td><input type="checkbox" id="card-{{$card->id}}" wire:model="selectedCards.{{$card->card_id}}" style="width:17px;height:17px;"></td>
                                    <th scope="row">{{$card->referencia}}</th>
                                    <td>{{$card->titulo}}</td>
                                    <td>{{$card->prioridade}}</td>
                                    <td>@if ( $card->estimativa && $card->extensao ) {{$card->estimativa}}{{$card->extensao}} @else Não estimada @endif </td>
                                    <td>{{date_format(date_create($card->data_hora_criacao),"d/m/Y H:i:s")}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-5" role="alert">
                    Adicione tarefas ao backlog para criar uma sprint.</br></br>
                    <a href="/backlog/{{$teamDataAndPermission['equipe_id']}}/{{$teamDataAndPermission['squad_id']}}">Retornar</a>
                </div>
            @endif
        @else
            <div class="alert alert-warning mt-5" role="alert">
                Você não possui cargos para gerenciar o backlog.</br></br>
                <a href="/backlog/{{$teamDataAndPermission['equipe_id']}}/{{$teamDataAndPermission['squad_id']}}">Retornar</a>
            </div>
        @endif
    @else
        <div class="alert alert-warning mt-5" role="alert">
            Não encontramos esta equipe.</br></br>
            <a href="/backlog/{{$teamDataAndPermission['equipe_id']}}/{{$teamDataAndPermission['squad_id']}}">Retornar</a>
        </div>
    @endif
    <script>
</div>
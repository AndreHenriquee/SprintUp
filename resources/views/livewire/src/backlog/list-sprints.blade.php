<div class="container">
    @if(isset($teamDataAndPermission['equipe_id']))
        @if($teamDataAndPermission['cargo'] != "ST")
            @if($sprints != null)
                <div class="row">
                    <div class="h2 col-4 pe-3 border-end border-dark">
                        Sprints da squad {{$teamDataAndPermission['squad_nome']}}
                    </div>
                    <div class="col-8 my-auto">
                        Visualize todos os detalhes das sprints da squad
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
                <div class="row mb-2 rounded p-3 mt-3" style="background-color:#f2f2f2">
                    <div class="col-12">
                        <div class="h3">Sprints em andamento</div>
                        <div class="row mt-3">
                            <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
                                <thead>
                                    <tr>
                                        <th scope="col">Numero</th>
                                        <th scope="col">Inicio</th>
                                        <th scope="col">Fim</th>
                                        <th scope="col">Duração da sprint</th>
                                        <th scope="col">Tarefas atribuídas</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($sprints as $sprint)
                                <tr>
                                    <th scope="row">{{$sprint->numero}}</th>
                                    <td>{{date_format(date_create($sprint->inicio),"d/m/Y")}}</td>
                                    <td>{{date_format(date_create($sprint->fim),"d/m/Y")}}</td>
                                    <td>{{$sprint->dias}} Dias</td>
                                    <td>{{$sprint->referencias}}</td>                                    
                                        @if($sprint->finalizada) <td>Finalizada</td>
                                        @elseif (!$sprint->finalizada && $currentTime <= $sprint->inicio) <td>Ainda não iniciada</td>
                                        @elseif (!$sprint->finalizada && $currentTime <= $sprint->fim) <td>Em andamento</td>
                                        @else <td>Atrasada</td> 
                                        @endif
                                    <td><input <?= $sprint->finalizada ? 'disabled' : '' ?> class=" btn btn-primary btn-dark" type="button" value="Finalizar Sprint" wire:click="endSprint({{$sprint->id}})"></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script>
                        [...document.querySelectorAll('input[type="text"]')].forEach(textInput => {
                            textInput.addEventListener('input', function(e) {
                                var input = e.target,
                                    hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden');

                                hiddenInput.value = input.value;

                                hiddenInput.dispatchEvent(new Event('input'));
                            })
                        });
                    </script>
                </div>
            @else
                <div class="alert alert-warning mt-5" role="alert">
                    Crie novas sprints para acompanhá-las por aqui.</br></br>
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
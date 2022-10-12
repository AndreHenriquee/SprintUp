<div class="container">
    @if(
    isset($teamDataAndPermission['nome'])
    )
    <div class="row">
        <div title="Squads da equipe {{$teamDataAndPermission['nome']}}" class="h1 col-4 pe-3 border-end border-dark text-truncate">
            Squads da equipe {{$teamDataAndPermission['nome']}}
        </div>
        <div title="Gerencie aqui as Squad da equipe {{$teamDataAndPermission['nome']}}" class="col-8 my-auto text-truncate">
            Gerencie aqui as Squad da equipe <b>{{$teamDataAndPermission['nome']}}</b>
        </div>
    </div>
    <hr class="opacity-100">
    <div class="row rounded p-3 mt-3 mb-2" style="background-color:#f2f2f2">
        <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $numberOfSquads = count($teamSquads); ?>
                @foreach($teamSquads as $teamSquad)
                <tr style="<?= $sessionParams['squad_id'] == $teamSquad->id ? 'font-weight:bolder;' : '' ?>">
                    <th scope="row">{{$teamSquad->referencia}}</th>
                    <td>{{$teamSquad->nome}}</td>
                    <td>{{$teamSquad->descricao}}</td>
                    <td>
                        @if($sessionParams['squad_id'] != $teamSquad->id)

                        @if($teamDataAndPermission['permissao_gerenciar_squads'])
                        <a title="Excluir Squad" class="text-dark" style="cursor:pointer; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#modalChangeSquads-{{$teamSquad->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                        </a>
                        <livewire:src.team-members.sure-to-exclude-squad-modal :squadData="(array) $teamSquad" :numberOfSquads="$numberOfSquads" />

                        @endif

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-warning mt-5" role="alert">
        Verifique se a equipe passada como parâmetro realmente existe.
    </div>
    @endif
</div>
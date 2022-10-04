<div class="container">
    <div class="row">
        <div class="h1 col-2 pe-3 border-end border-dark">
            Equipes
        </div>
        <div class="col-10 my-auto">
            Gerencie aqui as equipes às quais você tem acesso
        </div>
    </div>
    <hr class="opacity-100">
    <div class="row mb-3">
        <div class="col">
            <a href="/nova-equipe" class="btn btn-primary btn-dark">Criar nova equipe</a>
        </div>
    </div>
    <div class="row rounded p-3 mt-3 mb-2" style="background-color:#f2f2f2">
        <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Possui Roadmap?</th>
                    <th scope="col">Seu grupo de permissão</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userTeams as $userTeam)
                <tr>
                    <td>{{$userTeam->nome}}</td>
                    <td>{{$userTeam->descricao}}</td>
                    <td>{{$userTeam->roadmap_ativo}}</td>
                    <td>{{$userTeam->grupo_permissao}}</td>
                    <td>
                        @if($userTeam->permissao_link_convite)
                        <a href="/convite-equipe/{{$userTeam->id}}" class="text-dark" style="cursor:pointer; text-decoration: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-envelope-plus" viewBox="0 0 16 16">
                                <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z" />
                                <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5Z" />
                            </svg>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
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
                </tr>
            </thead>
            <tbody>
                @foreach($userTeams as $userTeam)
                <tr>
                    <td>{{$userTeam->nome}}</td>
                    <td>{{$userTeam->descricao}}</td>
                    <td>{{$userTeam->roadmap_ativo}}</td>
                    <td>{{$userTeam->grupo_permissao}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
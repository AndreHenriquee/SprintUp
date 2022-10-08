<div class="container">
    @if(
    isset($teamDataAndPermission['nome'])
    )
    <div class="row">
        <div title="Membros da equipe {{$teamDataAndPermission['nome']}}" class="h1 col-4 pe-3 border-end border-dark text-truncate">
            Membros da equipe {{$teamDataAndPermission['nome']}}
        </div>
        <div title="Gerencie aqui os membros da equipe {{$teamDataAndPermission['nome']}}" class="col-8 my-auto text-truncate">
            Gerencie aqui os membros da equipe <b>{{$teamDataAndPermission['nome']}}</b>
        </div>
    </div>
    <hr class="opacity-100">
    <div class="row rounded p-3 mt-3 mb-2" style="background-color:#f2f2f2">
        <table class="table table-hover h5 rounded" style="background-color:#e6e6e6;">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Grupo de permissão</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teamMembers as $teamMember)
                <tr style="<?= $sessionParams['usuario_id'] == $teamMember->id ? 'font-weight:bolder;' : '' ?>">
                    <td>{{$teamMember->nome}}</td>
                    <td>{{$teamMember->email}}</td>
                    <td>{{$teamMember->grupo_permissao_nome}}</td>
                    <td>
                        @if($sessionParams['usuario_id'] != $teamMember->id)

                        @if(
                        ($teamMember->grupo_permissao_id == 1 && $teamDataAndPermission['permissao_grupo_administrador']) ||
                        ($teamMember->grupo_permissao_id != 1 && $teamDataAndPermission['permissao_grupo_moderador_comum'])
                        )
                        <a title="Mudar permissões" class="text-dark me-3" style="cursor:pointer; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#modalChangePermission-{{$teamMember->id}}">
                            <svg xmlns=" http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                                <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
                                <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                            </svg>
                        </a>
                        <livewire:src.team-members.change-member-permission-modal :memberData="(array) $teamMember" :teamId="(int) $routeParams['equipe_id']" :allowedGroups="['permissao_grupo_administrador' => $teamDataAndPermission['permissao_grupo_administrador'], 'permissao_grupo_moderador_comum' => $teamDataAndPermission['permissao_grupo_moderador_comum']]" />
                        @endif

                        @if(
                        ($teamMember->grupo_permissao_id == 1 && $teamDataAndPermission['permissao_papel_administrador']) ||
                        ($teamMember->grupo_permissao_id != 1 && $teamDataAndPermission['permissao_papel_moderador_comum'])
                        )
                        <a title="Mudar papel Scrum" class="text-dark" style="cursor:pointer; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#modalChangeRole-{{$teamMember->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5v-1zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                            </svg>
                        </a>
                        <livewire:src.team-members.change-member-role-modal :memberData="(array) $teamMember" :teamId="(int) $routeParams['equipe_id']" :allowedGroups="['permissao_papel_administrador' => $teamDataAndPermission['permissao_papel_administrador'], 'permissao_papel_moderador_comum' => $teamDataAndPermission['permissao_papel_moderador_comum']]" />
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
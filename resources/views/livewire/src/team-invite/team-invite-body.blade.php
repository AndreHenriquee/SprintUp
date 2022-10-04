<div class="container">
    @if(
    isset($teamDataAndPermission['permissao_link_convite']) &&
    isset($teamDataAndPermission['permissao_link_convite_nome']) &&
    $teamDataAndPermission['permissao_link_convite']
    )
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Criar link de convite para a equipe</h2>
        </div>
        <div class="modal-body">
            Você está criando um link de convite para a equipe <b>{{$teamDataAndPermission['nome']}}</b>.
            <br /><br />
            Preencha os dados a seguir sobre os usuários que serão convidados:
            <div class="row">
                <div class="col-12 mt-4">
                    <select wire:model="squadId" class="form-select">
                        <option value="0">Squad do convidado</option>
                        @foreach($squads as $squad)
                        <option value="{{$squad->id}}">
                            {{$squad->nome}}
                        </option>
                        @endforeach
                    </select>
                    @error('squadId') <span class="text-danger error">Selecione uma squad</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <select wire:model="roleId" class="form-select">
                        <option value="0">Papel SCRUM do convidado na squad</option>
                        @foreach($roles as $role)
                        <option value="{{$role->id}}">
                            {{$role->nome}}
                        </option>
                        @endforeach
                    </select>
                    @error('roleId') <span class="text-danger error">Selecione um papel SCRUM</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <select wire:model="permissionGroupId" class="form-select">
                        <option value="0">Grupo de permissão do convidado na squad</option>
                        @foreach($permissionGroups as $permissionGroup)
                        <option value="{{$permissionGroup->id}}">
                            {{$permissionGroup->nome}}
                        </option>
                        @endforeach
                    </select>
                    @error('permissionGroupId') <span class="text-danger error">Selecione um grupo de permissão</span> @enderror
                </div>
                <div class="col-12 mt-2">
                    <input type="email" wire:model="email" class="form-control" placeholder="E-mail de convite" />
                    @error('email')
                    <span class="text-danger error">
                        Para enviar o link por e-mail, o e-mail em questão precisa constar neste campo e deve ser válido
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button wire:click="copyLink" type="button" class="btn btn-light border border-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.9rem" height="0.9rem" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                    <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
                    <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
                </svg>
                Copiar link
            </button>
            <button wire:click="sendLinkByEmail" type="button" class="btn btn-primary">Enviar convite para o e-mail</button>
            <script>
                document.addEventListener('livewire:load', function() {
                    Livewire.on('copyLinkToClipboard', inviteLink => {
                        navigator.clipboard.writeText(window.location.host + '/aceitar-link-convite/' + inviteLink);
                        alert('Link copiado para a sua área de transferência.');
                        window.location.href = '/equipes';
                    })

                    Livewire.on('inviteLinkSendedToEmail', () => {
                        alert('E-mail com o link de convite enviado com sucesso!');
                        window.location.href = '/equipes';
                    })

                    Livewire.on('alertNoPermission', () => {
                        alert('Você não tem permissão para convidar pessoas desse grupo de permissões a essa equipe.');
                    })
                });
            </script>
        </div>
    </div>
    @else
    <div class="alert alert-warning mt-5" role="alert">
        @if(isset($teamDataAndPermission['nome']))
        Você não tem permissão para {{$teamDataAndPermission['permissao_link_convite_nome']}} <b>{{$teamDataAndPermission['nome']}}</b>.
        @else
        Verifique se a equipe passada como parâmetro realmente existe.
        @endif
    </div>
    @endif
</div>
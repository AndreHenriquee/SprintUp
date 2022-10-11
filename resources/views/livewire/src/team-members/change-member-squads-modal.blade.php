<div wire:ignore.self class="modal fade" id="modalChangeSquads-{{$memberData['id']}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar Squads do usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você está alterando o acesso às Squads do usuário <b>{{$memberData['nome']}}</b></p>
                <div class="row bg-light p-2 rounded">
                    @foreach($teamSquads as $teamSquad)
                    <div class="col-12 mb-3">
                        <h6>Cargo na Squad <b>{{$teamSquad->nome}}</b>:</h6>
                        @if(empty($teamSquad->cargo_id))
                        <select wire:model="selectedRoleIdsPerSquad.{{$teamSquad->id}}" class="form-select">
                            <option value="0">Novo papel Scrum (usuário não está nesta Squad)</option>
                            @foreach($scrumRoles as $scrumRole)
                            <option value="{{$scrumRole->id}}">{{$scrumRole->nome}}</option>
                            @endforeach
                        </select>
                        @else
                        <div class="row">
                            <div class="col-10">
                                <input type="text" class="form-control" disabled value="{{$teamSquad->cargo_nome}}" />
                            </div>
                            <div class="col-2">
                                <div class="form-check form-switch">
                                    <input title="Manter na Squad" type="checkbox" role="switch" wire:model="isAtSquad.{{$teamSquad->id}}" title="Faz parte da equipe" class="form-check-input" style="cursor: pointer;" <?= $memberData['grupo_permissao_id'] == 1 ? 'disabled' : '' ?> />
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    <script>
                        document.addEventListener('livewire:load', function() {
                            [...document.querySelectorAll('input[type="checkbox"]')].forEach(element => {
                                element.checked = true;
                                element.dispatchEvent(new Event('change'));
                            });
                        });
                    </script>
                    <div class="col-12">
                        @error('doesSquadRolesChanged')
                        <hr />
                        <h6 class="text-danger error">Você precisa alterar o papel do usuário em ao menos uma Squad</h6>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="changeRolesPerSquad" type="button" class="btn btn-primary">Confirmar</button>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modalChangeRole-{{$memberData['id']}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar papel Scrum do usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você está alterando o papel Scrum do usuário <b>{{$memberData['nome']}}</b></p>
                <div class="row bg-light p-2 rounded">
                    @foreach($memberSquads as $memberSquad)
                    <div class="col-12 mb-3">
                        <h6>Papel para a Squad <b>{{$memberSquad->referencia}} | {{$memberSquad->nome}}</b>:</h6>
                        <select wire:model="selectedRoleIdsPerSquad.{{$memberSquad->id}}" class="form-select">
                            <option value="0">Novo papel Scrum</option>
                            @foreach($scrumRoles as $scrumRole)
                            <option <?= $memberSquad->cargo_id == $scrumRole->id ? 'disabled' : '' ?> value="{{$scrumRole->id}}">
                                {{$scrumRole->nome}} <?= $memberSquad->cargo_id == $scrumRole->id ? '(Papel atual nesta Squad)' : '' ?>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                    <div class="col-12">
                        @error('doesRolesChanged')
                        <hr />
                        <h6 class="text-danger error">Você precisa alterar o papel do usuário em ao menos uma Squad</h6>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="changeScrumRoles" type="button" class="btn btn-primary">Alterar</button>
            </div>
        </div>
    </div>
</div>
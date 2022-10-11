<div wire:ignore.self class="modal fade" id="modalChangePermission-{{$memberData['id']}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar permissões do usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você está alterando o grupo de permissões do usuário <b>{{$memberData['nome']}}</b></p>
                <p>Grupo de permissão:</p>
                <div class="row">
                    <div class="col-12">
                        <select wire:model="permissionGroupId" class="form-select">
                            <option value="0">Novo grupo de permissões</option>
                            @foreach($permissionGroups as $permissionGroup)

                            @if(
                            ($permissionGroup->id == 1 && $allowedGroups['permissao_grupo_administrador']) ||
                            ($permissionGroup->id != 1 && $allowedGroups['permissao_grupo_moderador_comum'])
                            )
                            <option <?= $memberData['grupo_permissao_id'] == $permissionGroup->id ? 'disabled' : '' ?> value="{{$permissionGroup->id}}">
                                {{$permissionGroup->nome}} <?= $memberData['grupo_permissao_id'] == $permissionGroup->id ? '(Grupo atual)' : '' ?>
                            </option>
                            @endif

                            @endforeach
                        </select>
                        @error('permissionGroupId') <span class="text-danger error">Selecione um grupo de permissão</span> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="changePermission" type="button" class="btn btn-primary">Alterar</button>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4 mb-4">
    <div class="row">
        <div class="col-12 col-sm-7 col-md-6 m-auto">
            <div class="card-body border-end border-light" id="teamForm">
                <span class="h2 col-8 my-auto text-truncate">Crie uma equipe</span>
                <input type="text" wire:model="nomeEquipe" class="form-control mt-4" placeholder="Nome da Equipe" />
                @error('nomeEquipe') <span class="text-danger error">{{ $message }}</span> @enderror
                <input type="text" wire:model="descEquipe" class="form-control my-4 py-2" placeholder="Descrição breve da equipe" />
                @error('descEquipe') <span class="text-danger error">{{ $message }}</span> @enderror
                <div class="form-check">
                    Deseja criar um Roadmap para sua equipe?
                    <input type="checkbox" wire:model="roadmapCheckbox" class="form-check-input">
                </div>
            </div>
            <div class="card-body border-end border-light">
                <span class="h2 col-8 my-auto text-truncate">Crie uma squad</span>
                <input type="text" wire:model="nomeSquad" class="form-control mt-4" placeholder="Nome da Squad" />
                @error('nomeSquad') <span class="text-danger error">{{ $message }}</span> @enderror
                <input type="text" wire:model="descSquad" class="form-control my-4 py-2" placeholder="Descrição breve da Squad" />
                @error('descSquad') <span class="text-danger error">{{ $message }}</span> @enderror
                <div class="text-center mt-3">
                    <button wire:click="registerTeam" class="btn btn-dark">Criar equipe</button>
                    <input type="button" wire:click="resetFields" class="btn btn-secondary" value="Limpar campos" />
                    <p class="mt-3">Certifique-se de que os campos foram preenchidos corretamente.</p>
                </div>
            </div>
        </div>
    </div>
</div>
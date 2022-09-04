<div class="container mt-2 pt-4">
    <div class="row">
        <div class="col-12 col-sm-7 col-md-6 m-auto">
        <div class="card border-0 shadow">
            <div class="card-body" id="userForm">
                <span  class="h2 col-8 my-auto text-truncate">Insira suas informações de usuário</span>
                <input type="text" wire:model="nome" class="form-control mt-4" placeholder="Nome" />
                    @error('nome') <span class="text-danger error">{{ 'O campo nome é obrigatório' }}</span>@enderror 
                <input type="email" wire:model="email" class="form-control mt-4" placeholder="E-mail" requirer/>
                    @error('email') <span class="text-danger error">{{ $message }}</span> @enderror 
                <input type="password" wire:model="senha" class="form-control mt-4" placeholder="Senha" />
                    @error('senha') <span class="text-danger">{{ $message }}</span> @enderror 
                <input type="date" class="form-control mt-4" wire:model="data_nascimento">
                    @error('data_nascimento') <span class="text-danger">{{ $message }}</span> @enderror 

            </div>
            <div class="card-body border-end border-light" id="teamForm">
                <span  class="h2 col-8 my-auto text-truncate">Crie uma equipe</span>
                <input type="text" wire:model="nomeEquipe" class="form-control mt-4" placeholder="Nome da Equipe" />
                    @error('nomeEquipe') <span class="text-danger error">{{ $message }}</span> @enderror 
                <input type="text" wire:model="descTime" class="form-control my-4 py-2" placeholder="Descrição breve da equipe" />
                <div class="form-check">
                    Deseja criar um Roadmap para sua equipe?
                    <input type="checkbox" wire:model="roadmapCheckbox" class="form-check-input">
                </div>
            </div>
            <div class="card-body border-end border-light">
                <span  class="h2 col-8 my-auto text-truncate">Crie uma squad</span>
                <input type="text" wire:model="nomeSquad" class="form-control mt-4" placeholder="Nome da Squad" />
                    @error('nomeSquad') <span class="text-danger error">{{ $message }}</span> @enderror 
                <input type="text" wire:model="descSquad" class="form-control my-4 py-2" placeholder="Descrição breve da Squad" />
                <div class="text-center mt-3">
                    <button wire:click="registerUser" class="btn btn-dark">Realizar Cadastro</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>



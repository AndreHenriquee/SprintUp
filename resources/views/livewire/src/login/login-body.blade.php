<div class="container mt-5 mb-4">
    <div class="row">
        <div class="col-12 col-sm-7 col-md-6 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div>
                        <span class="h2 col-8 my-auto">Bem vindo a Sprint Up!</span>
                    </div>
                    <span class="col-8 my-auto">insira suas credenciais</span>
                    <input type="email" wire:model="email" class="form-control mt-4" placeholder="E-mail" />
                    @error('email') <span class="text-danger error">{{ 'Insira um email válido' }}</span>@enderror
                    <input type="password" wire:model="senha" class="form-control mt-4" placeholder="Senha" />
                    @error('senha') <span class="text-danger error">{{ 'Insira uma senha válida' }}</span>@enderror
                    <div class="text-center mt-3">
                        <button wire:click="login" class="btn btn-dark mb-3">Entrar</button>
                        <div>
                            <span class="col-4 my-auto">Não possui login?</span>
                            <a href="/register" class="nav-link">Cadastre-se</a>
                            <a href="/recovery" class="nav-link">Esqueceu sua senha?</a>
                        </div>
                        @if(session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session()->get('error') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
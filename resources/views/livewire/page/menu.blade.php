<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <!-- O link para o qual esse botão redireciona dependerá do cargo -->
        <a class="navbar-brand" href="#">Sprint Up</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{$alias == 'kanban' ? 'active' : ''}}" aria-current="page" href="kanban">Quadro Kanban</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$alias == 'documentacoes' ? 'active' : ''}}" href="documentacoes">Documentações</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Trocar squad
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <h6 class="dropdown-header">Equipe 1</h6>
                        </li>
                        <li class="pl-4"><a class="dropdown-item" href="#">Squad 1</a></li>
                        <li><a class="dropdown-item" href="#">Squad 2</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <h6 class="dropdown-header">Equipe 2</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">Squad 3</a></li>
                    </ul>
                </li>
            </ul>
            <livewire:page.login-info />
        </div>
    </div>
</nav>
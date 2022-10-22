<div class="container mt-5 mb-4">
    <div class="row">
        <div class="col-12 col-sm-7 col-md-6 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="col-8">Bem vindo a Sprint Up!</h2>
                        </div>
                        <div class="col-12">
                            <p>Acompanhe por aqui as novidades de diversos produtos!</p>
                            <p>Selecione de qual time deseja visualizar as novidades:</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input class="form-control" list="avaibleTeams" id="teamRoadmapSelect" placeholder="Selecione um time">
                            <datalist id="avaibleTeams">
                                @foreach($teamsList as $team)
                                <option data-value="{{$team->id}}">
                                    {{$team->nome}}
                                </option>
                                @endforeach
                            </datalist>
                            <input class="d-none" type="text" wire:model="teamId" id="teamRoadmapSelect-hidden">
                            @error('teamId') <span class="text-danger error">Você precisa selecionar um time válido</span> @enderror

                            @if(!empty($selectedTeamData))
                            <div class="mt-3 p-2 rounded" style="background-color:#e6e6e6;">
                                <h5>Informações do time:</h5>
                                <p><b>Nome:</b> {{$selectedTeamData['nome']}}</p>
                                @if(!empty($selectedTeamData['descricao']))
                                <p><b>Descrição:</b> {{$selectedTeamData['descricao']}}</p>
                                @endif
                                <p><b>Número de produtos:</b> {{$selectedTeamData['numero_produtos']}}</p>
                                <p><b>Número de funcionalidades:</b> {{$selectedTeamData['numero_funcionalidades']}}</p>
                            </div>
                            @endif
                        </div>
                        <script>
                            [...document.querySelectorAll('input[list]')].forEach(datalist => {
                                datalist.addEventListener('input', function(e) {
                                    var input = e.target,
                                        list = input.getAttribute('list'),
                                        options = document.querySelectorAll('#' + list + ' option'),
                                        hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
                                        inputValue = input.value;

                                    hiddenInput.value = 'null';

                                    for (var i = 0; i < options.length; i++) {
                                        var option = options[i];

                                        if (option.innerText.trim() === inputValue.trim()) {
                                            hiddenInput.value = option.getAttribute('data-value');
                                            break;
                                        }
                                    }

                                    hiddenInput.dispatchEvent(new Event('input'));
                                });
                            });
                        </script>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <a wire:click="teamPreview" class="btn btn-secondary" style="cursor:pointer; text-decoration: none;">
                                Previsualizar dados do time
                            </a>
                            <a wire:click="goToTeamRoadmap" class="btn btn-primary" style="cursor:pointer; text-decoration: none;">
                                Explorar novidades
                            </a>
                        </div>
                        <div class="col-12 mt-3">
                            <p>Para avaliar as novidades e visualizar as avaliações de outros usuários, <a href="/login-cliente">entre com a sua conta</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
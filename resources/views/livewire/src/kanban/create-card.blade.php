<div>
    <div class="modal-content">
        <div class="modal-header">
            <div class="row" style="width:100%">
                <div class="col-lg">
                    <p class="form-label mb-2"><b>Titulo: </b> </p>
                    <input class="form-control mb-2" wire:model="titulo" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                    @error('titulo') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="row">
                <p class="form-label mb-2"><b>Descrição: </b> </p>
                <div class="col-sm-9">
                    <textarea class="form-control" wire:model="descricao" rows="15" style="background-color:rgba(195, 195, 195, 0.35);border:none"></textarea>
                </div>
                <div class="col-3 p-3 bg-light">
                    <div class="input-group mb-3">
                        <p class="form-label"><b>Relator:</b> 
                            {{$userInfo['usuario']['nome']}}
                        </p>
                    </div>
                    <p class="form-label mb-2">
                        <b>Responsável: </b> 
                    </p>
                    <input class="form-control mb-2 mt-2" autocomplete="off" list="squadMembers" id="selectedTaskMember" style="background-color:rgba(195, 195, 195, 0.35);border:none" placeholder="Selecione o responsável">
                    <datalist id="squadMembers">
                        @foreach($squadMembers as $squadMember)
                        <option data-value="{{$squadMember->id}}">
                            {{$squadMember->nome}} ({{$squadMember->email}})
                        </option>
                        @endforeach
                    </datalist>
                    <input class="d-none" type="text" wire:model="taskOwnerId" id="selectedTaskMember-hidden">
                    <script>
                        [...document.querySelectorAll('input[list]')].forEach(datalist => {
                            datalist.addEventListener('input', function(e) {
                                var input = e.target,
                                    list = input.getAttribute('list'),
                                    options = document.querySelectorAll('#' + list + ' option'),
                                    hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
                                    inputValue = input.value;
                        
                                hiddenInput.value = '0';
                        
                                for (var i = 0; i < options.length; i++) {
                                    var option = options[i];
                                    if (option.innerText.trim() === inputValue.trim()) {
                                        hiddenInput.value = option.getAttribute('data-value');
                                        break;
                                    }
                                }
                                hiddenInput.dispatchEvent(new Event('input'));
                            })
                        });
                    </script>
                    <p class="form-label mb-2"><b>Estimativa: </b> </p>
                    <div class="row">
                        <div class="col">
                            <input class="form-check-input" wire:model="estimativeRadio" name="estimativeRadio" id="hoursRadio" type="radio" value="H">
                            <label class="form-check-label" type="radio">
                                Horas
                            </label>
                        </div>

                        <div class="col">
                            <input class="form-check-input" wire:model="estimativeRadio" name="estimativeRadio" id="storyPointsRadio" type="radio" value="Sp">
                            <label class="form-check-label" type="radio">
                                Story Points
                            </label>
                        </div>
                    </div>
                    @if($estimativeRadio == "Sp")
                    <div style="" id="storyPoints">
                        <p class="form-label mt-3">
                            <b>Story Points: </b> 
                        </p>
                        <select class="form-select" wire:model="spValue" id="selectPointsEstimative" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                            <option value="" selected>Selecione a estimativa</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="8">8</option>
                            <option value="13">13</option>
                        </select>
                    </div>
                    @endif
                    @if($estimativeRadio == "H")
                    <div style="" id="hours">
                        <p class="form-label mt-3"><b>Horas: </b> </p>
                        <input type="number" id="inputId" class="form-control mb-2" wire:model="timeValue" style="background-color:rgba(195, 195, 195, 0.35);border:none">
                    </div>
                    @endif
                </div>
            </div>
            <div class="d-flex flex-row-reverse">
                <button wire:click.prevent="createCard" class="col-2 btn btn-dark mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#modalCard" style="width:100%">Criar Card</button>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('livewire:load', function (){
        Livewire.emit('setEstimatives');
    })
    </script>
</div>
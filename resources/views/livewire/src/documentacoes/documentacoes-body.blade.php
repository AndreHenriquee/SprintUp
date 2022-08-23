<div class="container">
    <div class="row">
        <div class="h1 col-4 pe-3 border-end border-dark">
            Documentações
        </div>
        <div class="col-8 my-auto">
            Veja aqui os registros de cerimônias e as documentações informativas da sua Equipe ou Squad
        </div>
    </div>
    <hr class="opacity-100">
    <div class="row mb-3">
        <div class="col-auto">
            <input wire:model="textFilter" class="form-control" id="textFilterInput" type="search" placeholder="Buscar">
        </div>
        <div class="col-auto">
            <input class="form-control" list="mentionedTasks" id="taskMentionSelect" placeholder="Tarefa mencionada">
            <datalist id="mentionedTasks">
                @foreach($taskMentions as $taskMention)
                <option data-value="{{$taskMention->id}}">
                    [{{$taskMention->referencia}}] {{$taskMention->titulo}}
                </option>
                @endforeach
            </datalist>
            <input class="d-none" type="text" wire:model="taskMentionIdFilter" id="taskMentionSelect-hidden">
        </div>
        <div class="col-auto">
            <input class="form-control" list="mentionedMember" id="memberMentionSelect" placeholder="Membro mencionado">
            <datalist id="mentionedMember">
                @foreach($memberMentions as $memberMention)
                <option data-value="{{$memberMention->id}}">
                    {{$memberMention->nome}} ({{$memberMention->email}})
                </option>
                @endforeach
            </datalist>
            <input class="d-none" type="text" wire:model="memberMentionIdFilter" id="memberMentionSelect-hidden">
        </div>
        <div class="col-auto">
            <input class="form-control" id="dateFilterInput" type="date">
            <input class="d-none" type="text" wire:model="dateFilter" id="dateFilterInput-hidden">
        </div>
        <div class="col-auto">
            <button wire:click="updateFilters" type="submit" class="btn btn-primary btn-dark">Buscar</button>
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
                })
            });

            [...document.querySelectorAll('input[type="date"]')].forEach(dateInput => {
                dateInput.addEventListener('input', function(e) {
                    var input = e.target,
                        hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden');

                    hiddenInput.value = input.value;

                    hiddenInput.dispatchEvent(new Event('input'));
                })
            });

            document.addEventListener('livewire:load', function() {
                var inputedText = "{{$routeParams['texto']}}";
                var selectedTaskMention = "{{$routeParams['mencao_tarefa']}}";
                var selectedMemberMention = "{{$routeParams['mencao_membro']}}";
                var selectedDate = "{{$routeParams['data']}}";

                var textFilterInput = document.getElementById('textFilterInput');
                textFilterInput.value = ['', 'null'].includes(inputedText) ? '' : inputedText;
                textFilterInput.dispatchEvent(new Event('input'));

                selectedTaskMention = ['', 'null'].includes(selectedTaskMention) ? '' : selectedTaskMention;
                selectedTaskMentionOption = document.querySelector('#mentionedTasks option[data-value="' + selectedTaskMention + '"]');
                document.getElementById('taskMentionSelect').value = selectedTaskMentionOption ?
                    selectedTaskMentionOption.innerText.trim() :
                    '';
                var taskMentionSelectHidden = document.getElementById('taskMentionSelect-hidden');
                taskMentionSelectHidden.value = selectedTaskMention;
                taskMentionSelectHidden.dispatchEvent(new Event('input'));

                selectedMemberMention = ['', 'null'].includes(selectedMemberMention) ? '' : selectedMemberMention;
                selectedMemberMentionOption = document.querySelector('#mentionedMember option[data-value="' + selectedMemberMention + '"]');
                document.getElementById('memberMentionSelect').value = selectedMemberMentionOption ?
                    selectedMemberMentionOption.innerText.trim() :
                    '';
                var memberMentionSelectHidden = document.getElementById('memberMentionSelect-hidden');
                memberMentionSelectHidden.value = selectedMemberMention;
                memberMentionSelectHidden.dispatchEvent(new Event('input'));

                selectedDate = ['', 'null'].includes(selectedDate) ? '' : selectedDate;
                document.getElementById('dateFilterInput').value = selectedDate;
                var dateFilterInputHidden = document.getElementById('dateFilterInput-hidden');
                dateFilterInputHidden.value = selectedDate;
                dateFilterInputHidden.dispatchEvent(new Event('input'));

                Livewire.emit('validateRouteParams');
            });
        </script>
    </div>
    <div class="row mb-2 rounded p-3 mt-3" style="background-color:#f2f2f2">
        <div class="col-12">
            <div class="h3">Informações</div>
            <div class="row p-2 mt-3">
                <livewire:src.documentacoes.collapse-document-list :tipo="'INFORMATION'" :textFilter="$routeParams['texto']" :taskMentionIdFilter="$routeParams['mencao_tarefa']" :memberMentionIdFilter="$routeParams['mencao_membro']" :dateFilter="$routeParams['data']" />
            </div>
        </div>
        <div class="col-12">
            <div class="h3">Registros de cerimônias</div>
            <div class="row p-2 mt-3">
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_PLANNING'" :textFilter="$routeParams['texto']" :taskMentionIdFilter="$routeParams['mencao_tarefa']" :memberMentionIdFilter="$routeParams['mencao_membro']" :dateFilter="$routeParams['data']" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'DAILY_SCRUM'" :textFilter="$routeParams['texto']" :taskMentionIdFilter="$routeParams['mencao_tarefa']" :memberMentionIdFilter="$routeParams['mencao_membro']" :dateFilter="$routeParams['data']" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_REVIEW'" :textFilter="$routeParams['texto']" :taskMentionIdFilter="$routeParams['mencao_tarefa']" :memberMentionIdFilter="$routeParams['mencao_membro']" :dateFilter="$routeParams['data']" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_RETROSPECTIVE'" :textFilter="$routeParams['texto']" :taskMentionIdFilter="$routeParams['mencao_tarefa']" :memberMentionIdFilter="$routeParams['mencao_membro']" :dateFilter="$routeParams['data']" />
            </div>
        </div>
    </div>
</div>
<div class="container {{empty($routeParams) ? '' : 'pt-4'}}">
    @if (empty($features['TO_DO']) && empty($features['DOING']) && empty($features['DONE']))
    <div class="alert alert-warning mt-5" role="alert">
        Esta equipe não tem nenhuma funcionalidade para mostrar
    </div>
    @else
    <div class="row">
        <div title="Roadmap | {{$teamData['nome']}}" class="h1 col-5 pe-3 text-truncate border-end border-dark">
            Roadmap | {{$teamData['nome']}}
        </div>
        <div class="col-7 my-auto">
            Aqui está o histórico e o planejamento das funcionalidades trabalhadas pela equipe <b>{{$teamData['nome']}}</b>
        </div>
    </div>
    <hr class="opacity-100">
    <div class="row mb-3 rounded p-3" style="background-color:#f2f2f2">
        <livewire:src.roadmap.collapse-roadmap-section :features="$features" :tipo="'TO_DO'" />
        <livewire:src.roadmap.collapse-roadmap-section :features="$features" :tipo="'DOING'" />
        <livewire:src.roadmap.collapse-roadmap-section :features="$features" :tipo="'DONE'" />
    </div>
    @endif
</div>
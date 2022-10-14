<div class="col-12">
    <div class="card my-2" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#collapseDocList-{{$tipo}}">
        <div class="row">
            <div class="col-10">
                <div class="card-body">
                    @if ($tipo != 'INFORMATION')
                    <div class="h4 card-title">{{$typeMap[$tipo]['titulo']}}</div>
                    @endif
                    <div class="h5 card-subtitle mb-2 text-muted">{{$typeMap[$tipo]['descricao']}}</div>
                </div>
            </div>
            <div class="col-2 pe-5 text-end my-auto text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" width="1.575rem" height="1.575rem" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                    <path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <livewire:src.documentacoes.document-list :teamDataAndPermission="$teamDataAndPermission" :tipo="$tipo" :typeMap="$typeMap" :filters="$unifiedFilters" />
        </div>
    </div>
</div>
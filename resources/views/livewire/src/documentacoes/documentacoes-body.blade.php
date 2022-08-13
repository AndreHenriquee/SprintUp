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
    <div>
    <div class="h3">Filtros</div>
    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <input class="form-control" type="search" placeholder="Buscar" id="buscar">
        </div>
            <div class="col-auto">
                <livewire:src.documentacoes.document-filter/>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" id="">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-dark">Buscar</button>
            </div>
        </div>
    </div>
    <div class="row mb-2 rounded p-3 mt-3" style="background-color:#f2f2f2">
        <div class="col-12">
            <div class="h3">Informações</div>
            <div class="row p-2 mt-3">
                <livewire:src.documentacoes.collapse-document-list :tipo="'INFORMATION'" />
            </div>
        </div>
        <div class="col-12">
            <div class="h3">Registros de cerimônias</div>
            <div class="row p-2 mt-3">
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_PLANNING'" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'DAILY_SCRUM'" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_REVIEW'" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_RETROSPECTIVE'" />
            </div>
        </div>
    </div>
</div>
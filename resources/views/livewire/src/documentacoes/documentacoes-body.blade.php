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
    <div class="row mb-3 rounded p-3" style="background-color:#f2f2f2">
        <div class="h3 col-12">
            Informações
            <div class="row p-2 mt-3">
                <livewire:src.documentacoes.collapse-document-list :tipo="'INFORMATION'" />
            </div>
        </div>
        <div class="h3 col-12">
            Registros de cerimônias
            <div class="row p-2 mt-3">
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_PLANNING'" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'DAILY_SCRUM'" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_REVIEW'" />
                <livewire:src.documentacoes.collapse-document-list :tipo="'SPRINT_RETROSPECTIVE'" />
            </div>
        </div>
    </div>
</div>
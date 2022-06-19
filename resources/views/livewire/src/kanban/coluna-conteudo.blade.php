<div class="col p-2 rounded-bottom ms-1 me-1 mb-1">
    @foreach ($cards as $card)
    <livewire:src.kanban.card :data="(array) $card" />
    @endforeach
</div>
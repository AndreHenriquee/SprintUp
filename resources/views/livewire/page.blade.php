<!DOCTYPE html>
<html>
<livewire:page.head :title="$title" />

<body class="bg-light">
    @if ($loadMenu)
    <livewire:page.menu :alias="$alias" />
    @endif
    @if (empty($routeParams))
    <livewire:is :component="$body" :alias="$alias" />
    @else
    <livewire:is :component="$body" :alias="$alias" :routeParams="$routeParams" />
    @endif
    <script src="/js/app.js"></script>
    <livewire:scripts />
</body>

</html>
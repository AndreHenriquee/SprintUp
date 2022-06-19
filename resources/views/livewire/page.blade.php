<!DOCTYPE html>
<html>
<livewire:page.head :title="$title" />

<body class="bg-light">
    @if ($loadMenu)
    <livewire:page.menu :alias="$alias" />
    @endif
    <livewire:is :component="$body" />
    <script src="/js/app.js"></script>
    <livewire:scripts />
</body>

</html>
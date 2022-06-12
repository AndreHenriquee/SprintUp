<!DOCTYPE html>
<html>
<livewire:page.head :title="$title" />

<body class="bg-light">
    <livewire:page.menu :pageAlias="$pageAlias" />
    <livewire:is :component="$body" />
    <script src="/js/app.js"></script>
</body>

</html>
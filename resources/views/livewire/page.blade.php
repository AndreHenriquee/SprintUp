<!DOCTYPE html>
<html>
<livewire:page.head :title="$title" />

<body class="bg-light">
    <?php
    if (
        in_array($alias, ['login', 'register']) ||
        (session('user_data') &&
            session('user_data')['usuario_id'] &&
            session('user_data')['squad_id']
        )
    ) {
    ?>
        @if ($loadMenu)
        <livewire:page.menu :alias="$alias" />
        @endif
        @if (empty($routeParams))
        <livewire:is :component="$body" :alias="$alias" />
        @else
        <livewire:is :component="$body" :alias="$alias" :routeParams="$routeParams" />
        @endif
    <?php
    } else {
        redirect('/');
    }
    ?>
    <script src="/js/app.js"></script>
    <livewire:scripts />
</body>

</html>
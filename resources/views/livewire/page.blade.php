<!DOCTYPE html>
<html>
<livewire:page.head :title="$title" />

<body class="bg-light">
    <?php
    $sessionUserData = session('user_data');
    if (
        in_array($alias, ['login', 'register']) ||
        ($sessionUserData &&
            $sessionUserData['usuario_id'] &&
            $sessionUserData['squad_id']
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
        if ($alias != 'aceitar-link-convite') {
            redirect('/login');
        } else {
            redirect('/login/' . $routeParams['hash_convite']);
        }
    }
    ?>
    <script src="/js/app.js"></script>
    <livewire:scripts />
</body>

</html>
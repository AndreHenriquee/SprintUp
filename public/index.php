<?php
//echo 'teste';
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Sprint Up</title>
</head>

<body>
    <style>
        body {
            background: #EBEBF0;
        }

        .col-auto {
            background: #657ED4;
            padding-left: 25px;
        }

        .card-title {
            cursor: move;
        }
    </style>

    <nav class="navbar navbar-expand-lg" style="background-color: #657ED4;">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="index.php">Home</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="navbar-brand text-white">algo</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Digite algo" ">
                    <button class=" btn btn-light" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row gx-5">
            <div class="col-auto">
                <h2 class=" fw-normal">Backlog</h1>
                    <div class="card pe-auto" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Teste</h5>
                            <p class="card-text">Teste do teste
                            <p class="card-text">
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Teste</h5>
                            <p class="card-text">Teste do teste
                            <p class="card-text">
                        </div>
                    </div>
            </div>
            <div class="col-auto">
                <h2 class="fw-normal">To do</h1>
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Teste</h5>
                            <p class="card-text">Teste do teste
                            <p class="card-text">
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Teste</h5>
                            <p class="card-text">Teste do teste
                            <p class="card-text">
                        </div>
                    </div>

            </div>
        </div>
        <script>
        </script>
</body>
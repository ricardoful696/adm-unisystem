<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/loginAdm.css') }}">
    <link rel="stylesheet" href="{{ asset('css/firstAcess.css') }}">
    <title>Primeiro Acesso</title>
</head>

<body class="d-flex justify-content-center align-items-center" style="width:100vw;height:100vh;">
    <div class="teste d-flex justify-content-center">
        <div class="form1">
            <form method="POST" action="/firstAcess" class="box">
                @csrf
                <h1>PRIMEIRO ACESSO</h1>
                <p id="text" class="text-muted">Para definir uma senha primeiro digite seu login</p>
                <input type="text" name="login" id="login" placeholder="Usuário">
                <button type="submit" id="firstbtn" class="but">Buscar</button>
            </form>
        </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

@if (isset($loginError))
    <div class="alert alert-danger mt-3" style="width: 100%; text-align: center;">
        {{ $loginError }}
    </div>
@endif

</body>

</html>
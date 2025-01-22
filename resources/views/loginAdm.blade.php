<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/loginAdm.css') }}">
    <title>Login</title>
</head>

<body class="d-flex justify-content-center align-items-center" style="width:100vw;height:100vh;">
    <div class="teste mt-5 d-flex justify-content-center">
        <form method="POST" action="{{ route('login.submit', ['empresa' => $empresa]) }}" class="box"> 
            @csrf 
            <h1>Login</h1>
            <p class="text-muted">Por favor, coloque seu usuário e senha!</p>
            <input type="text" name="login" placeholder="Usuário"> 
            <input type="password" name="password" placeholder="Senha">
            <button type="button" class="forgot text-muted" id="firstacess">Primeiro acesso?</button>
            <input type="submit" value="Login">
        </form>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        @if (isset($loginError))
        <div class="alert alert-danger mt-3" style="width: 100%; text-align: center;">
            {{ $loginError }}
        </div>
        @endif

</body>

<style>
    .forgot{
        background: transparent;
        border-style: none;
    }
</style>

<script>
    var empresa = "{{ $empresa }}"; 
    $('#firstacess').click(function() {
        window.location.href = "{{ route('firstAcessView') }}";
    });
</script>

</html>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/loginNoEmp.css') }}">
    <title>Selecionar Empresa</title>
</head>

<body class="d-flex justify-content-center align-items-center" style="width:100vw;height:100vh;">
    <div class="teste d-flex justify-content-center">
        <div class="box" >
            <h1>Empresas</h1>
            <p class="text-muted">Por favor, selecione a empresa</p>
            <select id="empresa" name="empresa" required>
                <option value="">-- Escolha uma empresa --</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->nome_fantasia }}">{{ $empresa->nome }}</option>
                @endforeach
            </select>
            <input type="button" id="selectEmpresa" value="Selecionar">
        </div>
    </div>

    <script>
        document.getElementById('selectEmpresa').addEventListener('click', function () {
            var nomeFantasia = document.getElementById('empresa').value;
            console.log(nomeFantasia);

            if (!nomeFantasia) {
                alert('Por favor, selecione uma empresa.');
                return;
            }

            window.location.href = `/${nomeFantasia}/login`;
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

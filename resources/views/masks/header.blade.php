<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sidebar With Bootstrap</title>
    <link rel="stylesheet" href="/css/sidebar.css">
    @if (isset($css))
        <link rel="stylesheet" href="{{ asset($css) }}">
    @endif
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/pt-br.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>


<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">UniSystem</a>
                </div>
            </div>
            <div class="" >
                <ul class="sidebar-nav d-flex flex-column justify-content-between">
                    @if(session('adm') === true)
                    <div>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#Configuracao" aria-expanded="false" aria-controls="Configuracao">
                                <i class="lni lni-cog"></i>
                                <span class="class-tit">Configuraçôes</span>
                            </a>
                            <ul id="Configuracao" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item text-center">
                                    <a href="/enterpriseDevConfig" class="sidebar-link">Empresa</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/createCalendar" class="sidebar-link">Calendario</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/userDevConfig" class="sidebar-link">Usuário</a>
                                </li>
                            </ul>
                        </li>
                    </div>
                    @else
                    <div>
                        <li class="sidebar-item">
                            <a href="/myAccount" class="sidebar-link">
                            <i class="bi bi-person"></i>
                            <span class="class-tit">Minha Conta</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#Empresa" aria-expanded="false" aria-controls="Empresa">
                                <i class="bi bi-buildings"></i>
                                <span class="class-tit">Empresa</span>
                            </a>
                            <ul id="Empresa" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item text-center">
                                    <a href="/enterpriseData" class="sidebar-link">Dados da Empresa</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/enterpriseLayout" class="sidebar-link">Layout da Empresa</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="/calendar" class="sidebar-link">
                            <i class="bi bi-calendar-date"></i>
                            <span class="class-tit">Calendario</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#Produto" aria-expanded="false" aria-controls="Produto">
                                <i class="bi bi-gift"></i>
                                <span class="class-tit">Produto</span>
                            </a>
                            <ul id="Produto" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item text-center">
                                    <a href="/newProduct" class="sidebar-link">Novo Produto</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/productView" class="sidebar-link">Editar Produtos</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/categoryView" class="sidebar-link">Categorias</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#Campanha" aria-expanded="false" aria-controls="Campanha">
                                <i class="bi bi-newspaper"></i>
                                <span class="class-tit">Campanha</span>
                            </a>
                            <ul id="Campanha" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item text-center">
                                    <a href="/newCampaignView" class="sidebar-link">Nova Campanha</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/campaignView" class="sidebar-link">Editar Campanhas</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#Lote" aria-expanded="false" aria-controls="Lote">
                                <i class="bi bi-box-seam"></i>
                                <span class="class-tit">Lote</span>
                            </a>
                            <ul id="Lote" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item text-center">
                                    <a href="/newBatchView" class="sidebar-link">Novo Lote</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/batchView" class="sidebar-link">Editar Lotes</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="/enterprisePaymentView" class="sidebar-link">
                            <i class="bi bi-credit-card"></i>
                            <span class="class-tit">Empresa Pagamento</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#Configuracao" aria-expanded="false" aria-controls="Configuracao">
                                <i class="lni lni-cog"></i>
                                <span class="class-tit">Configuraçôes</span>
                            </a>
                            <ul id="Configuracao" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item text-center">
                                    <a href="/enterpriseConfig" class="sidebar-link">Configuraçôes da Empresa</a>
                                </li>
                                <li class="sidebar-item text-center">
                                    <a href="/enterprisePaymentConfig" class="sidebar-link">Configuraçôes de Pagamento</a>
                                </li>
                            </ul>
                        </li>
                    </div>
                @endif
                </ul>
            </div>
            
            <div class="sidebar-footer d-flex align-items-center justify-content-center ">
                <a href="/logout" class="sidebar-link align-items-center logout justify-content-center">
                    <i class="lni lni-exit"></i>
                    <span class="class-tit">Logout</span>
                </a>
            </div>
        </aside>
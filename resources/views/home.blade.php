@include('masks.header', ['css' => 'css/home.css'])


<div class="d-flex flex-column justify-content-center px-3 col-11">
    <h2 class="text-center mt-4">Dashboard de Vendas</h2>
    <div class="d-flex flex-wrap justify-content-center mt-5">
        <div class="px-2 mb-4" style="min-width: 250px; max-width: 250px;  flex-grow: 1;">
            <div class="card" style="height: 150px;">
                <h3 class="text-center font-semibold mb-2 px-4">Ingressos Vendidos Hoje</h3>
                <h3 class="text-center font-bold mt-3">{{ $ingressosVendidosHoje }}</h3>
            </div>
        </div>
        <div class="px-2 mb-4" style="min-width: 250px; max-width: 250px; flex-grow: 1;">
            <div class="card" style="height: 150px;">
                <h3 class="text-center font-semibold mb-2 px-4">Capacidade Máxima</h3>
                <h3 class="text-center font-bold mt-3">{{ $capacidadeMaxima ?? '∞' }}</h3>
            </div>
        </div>
        <div class="px-2 mb-4" style="min-width: 250px; max-width: 250px; flex-grow: 1;">
            <div class="card" style="height: 150px;">
                <h3 class="text-center font-semibold mb-2 px-4">Vendas Mensais</h3>
                <h3 class="text-center font-bold mt-3">{{ $vendasMensais }}</h3>
            </div>
        </div>
        <div class="px-2 mb-4" style="min-width: 250px; max-width: 250px; flex-grow: 1;">
            <div class="card" style="height: 150px;">
                <h3 class="text-center font-semibold mb-2 px-4">Média Vendas Diária</h3>
                <h3 class="text-center font-bold mt-3">
                    {{ round(array_sum($graficoVendasDiarias) / count($graficoVendasDiarias)) }}
                </h3>

            </div>
        </div>
    </div>
    <div class="d-flex flex-column gap-4">
        <div class="bg-white p-4 rounded-lg shadow-md mt-8 justify-center">
            <h2 class="text-lg font-semibold mb-4">Tipos de Ingressos Mais Vendidos</h2>
            <div class="pie-chart">
                <canvas class="small-chart" id="tiposIngressosChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4">Gráfico de Vendas Mensais</h2>
            <canvas id="vendasMensaisChart" height="100"></canvas>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4">Gráfico de Vendas Diárias (Últimos 30 dias)</h2>
            <canvas id="vendasDiariasChart" height="100"></canvas>
        </div>
    </div>
</div>

@include('masks.footer')

<script>
    const ctxVendasMensais = document.getElementById('vendasMensaisChart').getContext('2d');
    const vendasMensaisChart = new Chart(ctxVendasMensais, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($graficoVendasMensais)) !!},
            datasets: [{
                label: 'Vendas Mensais',
                data: {!! json_encode(array_map('intval', array_values($graficoVendasMensais))) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function (value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                }
            }
        }
    });

    const ctxVendasDiarias = document.getElementById('vendasDiariasChart').getContext('2d');

    const labelsDiarias = {!! json_encode(array_keys($graficoVendasDiarias)) !!};
    const dataDiarias = {!! json_encode(array_map('intval', array_values($graficoVendasDiarias))) !!};

    const vendasDiariasChart = new Chart(ctxVendasDiarias, {
        type: 'line',
        data: {
            labels: labelsDiarias,
            datasets: [{
                label: 'Vendas Diárias',
                data: dataDiarias,
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 3,
                lineTension: 0.1,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        autoSkip: true,
                        maxRotation: 45,
                        minRotation: 30
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function (value) {
                            return Number.isInteger(value) ? value : ''; s
                        }
                    }
                }
            }
        }
    });

    const ctxIngressos = document.getElementById('tiposIngressosChart').getContext('2d');

    const labelsIngressos = {!! json_encode($tiposIngressos->pluck('label')) !!};
    const quantidadeIngressos = {!! json_encode($tiposIngressos->pluck('quantidade_vendida')) !!};

    const tiposIngressosChart = new Chart(ctxIngressos, {
        type: 'pie',
        data: {
            labels: labelsIngressos,
            datasets: [{
                label: 'Ingressos Vendidos',
                data: quantidadeIngressos,
                backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' Ingressos';
                        }
                    }
                }
            }
        }
    });
</script>
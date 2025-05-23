@include('masks.header', ['css' => 'css/calendar.css'])

<div class="container mt-5">
    <h2 class="mb-4 text-center">Relatório de Vendas</h2>
    <p>Período: {{ $dataInicio }} até {{ $dataFinal }}</p>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID da Venda</th>
                <th>Data da Venda</th>
                <th>Metodo Pagamento</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @if ($vendas->isEmpty()) <!-- Verifica se a coleção está vazia -->
                <tr>
                    <td colspan="2" class="text-center">Nenhuma venda encontrada para o período selecionado.</td>
                </tr>
            @else
                @foreach ($vendas as $venda)
                    <tr>
                        <td>{{ $venda->venda_id }}</td> <!-- Correção para 'venda_id' -->
                        <td>{{ \Carbon\Carbon::parse($venda->data)->format('d/m/Y') }}</td> 
                        <td>{{ ucfirst($venda->metodoPagamento->descricao) ?? 'Método de pagamento não encontrado' }}</td>
                        <td>{{ $venda->valor}}</td> 
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@include('masks.footer')

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaProduto;
use App\Models\Empresa;
use App\Models\Venda;
use App\Models\VendaProduto;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use function PHPUnit\Framework\assertFileDoesNotExist;


class DashboardController extends Controller
{
    public function home()
    {
        $empresa = Session::get('empresa');

        if (!$empresa) {
            Session::flash('error', 'Nenhuma empresa encontrada na sessão.');
            return redirect()->route('login'); // Ou qualquer outra rota apropriada
        }

        $dados = Empresa::with('parametroEmpresa')
            ->where('nome_fantasia', $empresa)
            ->first();

        if (!$dados) {
            Session::flash('error', 'Empresa não encontrada no banco de dados.');
            return redirect()->route('login');
        }

        $capacidadeMaxima = optional($dados->parametroEmpresa)->valor_max_diario_Venda ?? 0;
        $ingressosVendidosHoje = $this->ingressosVendidosHoje() ?? 0;
        $vendasMensais = $this->vendasMensais() ?? 0;
        $graficoVendasMensais = $this->graficoVendasMensais() ?? [];
        $graficoVendasDiarias = $this->graficoVendasDiarias() ?? [];
        $tiposIngressos = $this->tiposIngressos() ?? [];

        return view('home', compact(
            'ingressosVendidosHoje',
            'capacidadeMaxima',
            'vendasMensais',
            'graficoVendasMensais',
            'graficoVendasDiarias',
            'tiposIngressos'
        ));
    }

    public function ingressosVendidosHoje()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();

        $dataHoje = Carbon::now()->toDateString();

        $contagemIngressos = Venda::where('empresa_id', $dados->empresa_id)
            ->whereHas('vendaProduto', function ($query) use ($dataHoje) {
                $query->whereDate('data_destino', $dataHoje);
            })
            ->count();

        return $contagemIngressos;
    }
    public function vendasMensais()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();

        $dataInicioMes = Carbon::now()->startOfMonth()->toDateString();
        $dataFimMes = Carbon::now()->endOfMonth()->toDateString();

        $totalVendasMensais = Venda::where('empresa_id', $dados->empresa_id)
            ->whereBetween('data', [$dataInicioMes, $dataFimMes])
            ->count();

        return $totalVendasMensais;
    }
    public function graficoVendasMensais()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();

        $vendasPorMes = Venda::selectRaw('EXTRACT(MONTH FROM data) as mes, COUNT(*) as total')
            ->where('empresa_id', $dados->empresa_id)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $graficoVendasMensais = [
            'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
            'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
            'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
        ];

        foreach ($vendasPorMes as $venda) {
            $mesNome = Carbon::create()->month((int) $venda->mes)->format('M');
            $graficoVendasMensais[$mesNome] = $venda->total;
        }

        return $graficoVendasMensais;
    }
    public function graficoVendasDiarias()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();

        $dataFinal = Carbon::now()->toDateString();
        $dataInicial = Carbon::now()->subDays(29)->toDateString(); 
        $vendasPorDia = Venda::selectRaw('DATE(data) as dia, COUNT(*) as total')
            ->where('empresa_id', $dados->empresa_id)
            ->whereBetween('data', [$dataInicial, $dataFinal])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $graficoVendasDiarias = [];
        for ($i = 29; $i >= 0; $i--) {
            $dia = Carbon::now()->subDays($i)->toDateString();
            $graficoVendasDiarias[$dia] = 0;
        }

        foreach ($vendasPorDia as $venda) {
            $graficoVendasDiarias[$venda->dia] = $venda->total;
        }

        return $graficoVendasDiarias;
    }

    public function tiposIngressos()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();

        $vendasPorTipo = VendaProduto::selectRaw('produto_id, COUNT(*) as quantidade_vendida')
            ->whereHas('venda', function ($query) use ($dados) {
                $query->where('empresa_id', $dados->empresa_id);
            })
            ->groupBy('produto_id')
            ->with('produto') 
            ->with('venda')
            ->get();

        $tiposIngressos = $vendasPorTipo->map(function ($venda) {
            return [
                'label' => $venda->produto->titulo,
                'quantidade_vendida' => $venda->quantidade_vendida,
            ];
        });

        return $tiposIngressos;
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\EmpresaImgTamanho;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\EmpresaImg;
use App\Models\Produto;
use App\Models\ProdutoPreco;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class EmpresaController extends Controller
{
    public function viewHome($empresa)
    {
        $dados = Empresa::where('nome_fantasia', $empresa)
                       ->with('empresaImg') 
                       ->firstOrFail();
        session(['empresa' => $empresa]);
        return view('home', compact('dados', 'empresa'));
    }
    
    public function presale(Request $request)
    {
        $data = $request->query('data');
        $nomeEmpresa = Session::get('empresa');
        $empresa = Empresa::where('nome_fantasia', $nomeEmpresa)->firstOrFail();
        $produtos = Produto::where('empresa_id', $empresa->empresa_id)
                   ->with('produtosPreco')
                   ->get();

        return view('presale', compact('produtos', 'data')); 
    }

    public function enterpriseDataView(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('empresa_id', $empresaId)->first();

        return view('enterpriseData', compact('empresa'));
    }
    
    public function enterpriseLayoutView(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('empresa_id', $empresaId)
        ->with('empresaImg')
        ->first();

        $colunas = Schema::getColumnListing('empresa_img');
        $colunas = array_filter($colunas, function ($coluna) {
            return !in_array($coluna, ['empresa_img_id', 'empresa_id']); // Exclua campos irrelevantes
        });

        $imgTamanho = EmpresaImgTamanho::first();

        return view('enterpriseLayout', compact('empresa', 'colunas', 'imgTamanho'));
    }

    public function saveLayout(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
    
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'campo' => 'required|string',
        ]);
    
        $campo = $request->campo;
        $empresaImg = EmpresaImg::where('empresa_id', $empresaId)->first();
    
        if (!$empresaImg) {
            return response()->json(['success' => false, 'message' => 'Empresa não encontrada.']);
        }
    
        $path = $request->file('image')->store('empresa_img', 'public');
    
        $appUrl = env('APP_URL'); 
    
        $fullPath = $appUrl . '/storage/' . $path;
    
        $empresaImg->{$campo} = $fullPath;
        $empresaImg->save();
    
        return response()->json([
            'success' => true,
            'message' => "Imagem para o campo '$campo' foi salva com sucesso.",
            'image_path' => $fullPath,
        ]);
    }
    
    

    public function enterpriseDataUpdate(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
    
        DB::beginTransaction();
    
        try {
            $empresa = Empresa::find($empresaId);
    
            if (!$empresa) {
                return response()->json(['error' => 'Empresa não encontrada.'], 404);
            }
    
            $empresa->update([
                'ativo' => $request->ativo,
                'nome' => $request->nome,
                'nome_fantasia' => $request->nome_fantasia,
                'cnpj' => $request->cnpj,
                'telefone1' => $request->telefone1,
                'telefone2' => $request->telefone2,
                'email' => $request->email,
                'cep' => $request->cep,
                'estado' => $request->estado,
                'cidade' => $request->cidade,
                'endereco' => $request->endereco,
                'endereco_numero' => $request->endereco_numero,
                'endereco_complemento' => $request->endereco_complemento,
                'url_capa' => $request->url_capa,
                'url_site' => $request->url_site,
                'url_facebook' => $request->url_facebook,
                'url_instagram' => $request->url_instagram,
            ]);
    
            DB::commit();

            return response()->json(['message' => 'Dados atualizados com sucesso.']);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['error' => 'Erro ao atualizar dados: ' . $e->getMessage()], 500);
        }
    }
    
    
}

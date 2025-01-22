<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function myAccount(Request $request)
    {   
        $usuario = auth()->user();
        return view('myAccount', compact('usuario'));
    }
    public function myAccountUpdate(Request $request)
    {   
        $Usuario = auth()->user();
        $nome = $request->input('nome');
        $sobrenome = $request->input('sobrenome');
        $email = $request->input('email');
        $documento = $request->input('documento');
        $telefone = $request->input('telefone');
        $nascimento = $request->input('data_nascimento');
        $sexo = $request->input('sexo');

        DB::beginTransaction();

        try {
            $Usuario->update([
                'nome' => $nome,
                'sobrenome' => $sobrenome,
                'email' => $email,
                'telefone' => $telefone,
                'documento' => $documento,
                'nascimento' => $nascimento,
                'sexo' => $sexo,
            ]);

            DB::commit();

            return response()->json(['message' => 'Usuário atualizado com sucesso!'], 200);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json(['message' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
        }
    }

    public function viewAccount($empresa, Request $request)
    {
        $data = $request->query('data');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        return view('account', compact('dados', 'empresa', 'data'));
    }
    public function viewPersonalData($empresa, Request $request)
    {
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $usuario = auth()->user();
        return view('personalData', compact('dados', 'empresa', 'usuario'));
    }
    public function viewMyPurchases($empresa, Request $request)
    {
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $usuario = auth()->user();
        return view('myPurchases', compact('dados', 'empresa', 'usuario'));
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'senhaAtual' => 'required|string',
            'novaSenha' => [
                'required',
                'string',
                'min:6',
                'regex:/[A-Za-z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'novaSenha.required' => 'A nova senha é obrigatória.',
            'novaSenha.min' => 'A nova senha deve ter pelo menos 6 caracteres.',
            'novaSenha.regex' => 'A nova senha deve conter pelo menos uma letra e um número.',
        ]);
        

        $senhaAtual = md5($request->input('senhaAtual'));
        $novaSenha = md5($request->input('novaSenha'));

        $usuario = auth()->user();

        if ($usuario->senha === $senhaAtual) {
            DB::beginTransaction();

            try {
                $usuario->senha = $novaSenha; 
                $usuario->save(); 

                DB::commit();

                return response()->json(['message' => 'Senha alterada com sucesso!'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Erro ao alterar a senha: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'Senha atual inválida.'], 403);
        }
    }
    public function updateData(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'documento' => 'required|string|max:14',
            'dataNascimento' => 'required|date',
            'telefone' => 'required|string|max:15',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'sobrenome.required' => 'O sobrenome é obrigatório.',
            'documento.required' => 'O documento é obrigatório.',
            'dataNascimento.required' => 'A data de nascimento é obrigatória.',
            'telefone.required' => 'O telefone é obrigatório.',
        ]);

        try {
            $usuario = auth()->user();
            $usuario->nome = $request->input('nome');
            $usuario->sobrenome = $request->input('sobrenome');
            $usuario->documento = $request->input('documento');
            $usuario->data_nascimento = $request->input('dataNascimento');
            $usuario->telefone = $request->input('telefone');
            $usuario->save();

            return response()->json(['message' => 'Dados alterados com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar os dados: ' . $e->getMessage()], 500);
        }
    }
    public function updateAdress(Request $request)
    {
        $request->validate([
            'endereco' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:50',
            'bairro' => 'nullable|string|max:100',
            'cep' => 'nullable|string|max:10',
            'cidade' => 'required|string|max:255',
        ], [
            'cidade.required' => 'A cidade é obrigatória.',
        ]);

        try {
            $usuario = auth()->user();
            $usuario->endereco = $request->input('endereco');
            $usuario->endereco_numero = $request->input('numero');
            $usuario->endereco_complemento = $request->input('complemento');
            $usuario->bairro = $request->input('bairro');
            $usuario->cep = $request->input('cep');
            $usuario->cidade = $request->input('cidade');
            $usuario->save();

            return response()->json(['message' => 'Endereço alterado com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o endereço: ' . $e->getMessage()], 500);
        }
    }

    

}

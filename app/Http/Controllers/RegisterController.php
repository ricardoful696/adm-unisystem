<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function viewRegister($empresa)
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        return view('register', compact('dados', 'empresa'));
    }

    public function registerSave(Request $request)
    {
        $nomeEmpresa = Session::get('empresa');
        $empresa = Empresa::where('nome_fantasia', $nomeEmpresa)->firstOrFail();
        $empresaId = $empresa->empresa_id;

        $nome = $request->input('nome');
        $sobrenome = $request->input('sobrenome');
        $email = $request->input('email');
        $documento = $request->input('documento');
        $telefone = $request->input('telefone');
        $nascimento = $request->input('nascimento');
        $sexo = $request->input('sexo');
        $cidade = $request->input('cidade');
        $password = $request->input('password1');

        DB::beginTransaction();

        try {
            Usuario::create([
                'empresa_id' => $empresaId,
                'nome' => $nome,
                'sobrenome' => $sobrenome,
                'email' => $email,
                'documento' => $documento,
                'telefone' => $telefone,
                'data_nascimento' => $nascimento,
                'sexo' => $sexo,
                'cidade' => $cidade,
                'senha' => md5($password),
            ]);

            DB::commit();

            return response()->json(['message' => 'Usuário registrado com sucesso!'], 200);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json(['message' => 'Erro ao registrar o usuário: ' . $e->getMessage()], 500);
        }
    }

    /*public function loginVerify(Request $request)
    {
        $login = $request->input('login');
        $dados = Usuario::where('user_name', $login)->exists();

        if ($dados) {
            return response()->json(['message' => 'existe'], 200);
        } else {
            return response()->json(['message' => 'disponivel'], 200);
        }

    }*/

    public function emailVerify(Request $request)
    {
        $email = $request->input('email');
        $nomeEmpresa = Session::get('empresa');
        $empresa = Empresa::where('nome_fantasia', $nomeEmpresa)->firstOrFail();
        $empresaId = $empresa->id;
        $dados = Usuario::where('email', $email)
                         ->where('empresa_id', $empresaId) 
                         ->exists();

        if ($dados) {
            return response()->json(['message' => 'existe'], 200);
        } else {
            return response()->json(['message' => 'disponivel'], 200);
        }

    }

    public function documentVerify(Request $request)
    {
        $document = $request->input('document');
        $nomeEmpresa = Session::get('empresa');
        $empresa = Empresa::where('nome_fantasia', $nomeEmpresa)->firstOrFail();
        $empresaId = $empresa->id;
        $dados = Usuario::where('documento', $document)
                         ->where('empresa_id', $empresaId) 
                         ->exists();

        if ($dados) {
            return response()->json(['message' => 'existe'], 200);
        } else {
            return response()->json(['message' => 'disponivel'], 200);
        }

    }
}

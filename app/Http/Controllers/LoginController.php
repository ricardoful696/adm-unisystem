<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    public function selectEmp(Request $request)
    {
        $empresas = Empresa::all();

        return view('loginNoEmp', compact('empresas'));

    }

    public function showLoginForm($empresa, Request $request)
    {
        $dados = Empresa::where('nome_fantasia', $empresa)
            ->with('empresaImg')
            ->firstOrFail();
        session(['empresa' => $empresa]);
        if (session()->has('loginError')) {
            $loginError = session('loginError');
            session()->forget('loginError');
            return view('loginAdm', compact('loginError', 'empresa'));
        }

        if ($request->isMethod('post')) {
            if (Auth::attempt($request->only('login', 'password'))) {
                return redirect()->intended('index');
            } else {
                session()->flash('loginError', 'Credenciais inválidas');
                return back();
            }
        }
        return view('loginAdm', compact('empresa'));

    }

    public function login(Request $request)
    {   
        Auth::logout();

        $empresa = Session::get('empresa');

        if(!$empresa){
            Session::flash('loginError', 'Senha incorreta');
            return redirect()->route('selectEmp');
        }

        $login = $request->get('login');
        $password = $request->input('password');
        $passwordMd5 = md5($password);

        if ($login == 'adm') {
            $generatedPassword = $this->generateSuperSenha();
            $user = Usuario::where('tipo_usuario_id', 3)->first();
            $SuperUserPassword = $user->senha;

            
            $nomeEmpresa = $empresa->nome_fantasia;
            $empresaId = $empresa->empresa_id;
            if ($user->empresa_id == $empresaId) {
                if ($generatedPassword === $password || $passwordMd5 == $SuperUserPassword) {
                    $request->session()->put('nomeusuario', $user->nome);
                    Session::put('empresa', 'Uni System');
                    Auth::login($user);
                    session(['adm' => true]);
                    return redirect()->route('home');
                } else {
                    Session::flash('loginError', 'Senha incorreta');
                }
            } else {
                Session::flash('loginError', 'Senha incorreta');
            }
        } else {
            $empresa = Session::get('empresa');
            $nomeEmpresa = $empresa->nome_fantasia;
            $empresaId = $empresa->empresa_id;
            $user = Usuario::where('login', $login)->first();

            if ($user && $user->tipo_usuario_id === 2) {
                if ($user->empresa_id === $empresaId) {
                    if ($user) {
                        if ($user->ativo) {
                            if ($user->senha === null) {
                                Session::flash('loginError', 'Esse usuário ainda não configurou o primeiro acesso.');
                            } else {
                                if ($passwordMd5 === $user->senha) {
                                    Auth::login($user);
                                    $request->session()->put('nomeusuario', $user->nome);
                                    $user->tentativas_login = null;
                                    $user->save();
                                    Session::put('empresa', $nomeEmpresa);

                                    return redirect()->route('home');
                                } else {
                                    if ($user->tentativas_login === 2) {
                                        $user->tentativas_login = $user->tentativas_login + 1;
                                        $user->ativo = false;
                                        $user->save();
                                        Session::flash('loginError', 'Senha incorreta. O usuário foi desativado devido ao número de tentativas de login excedido.');
                                    } else {
                                        if ($user->tentativas_login === null) {
                                            $user->tentativas_login = 1;
                                            $user->save();
                                        } else {
                                            $user->tentativas_login = $user->tentativas_login + 1;
                                            $user->save();
                                        }
                                        Session::flash('loginError', 'Senha incorreta');
                                    }
                                }
                            }
                        } else {
                            Session::flash('loginError', 'Usuário inativo');
                        }
                    } else {
                        Session::flash('loginError', 'Usuário não encontrado');
                    }
                } else {
                    Session::flash('loginError', 'Esse usuário não pertence a esta empresa');
                }
            } else {
                Session::flash('loginError', 'Esse usuário não é um administrador');
            }
        }

        return redirect()->route('showLoginForm', ['empresa' => $nomeEmpresa]);
    }

    public function generateSuperSenha()
    {
        $currentDate = date('dmY');
        $currentDate = preg_replace('/\D/', '', $currentDate);
        $currentTime = date('His');
        $currentTime = preg_replace('/\D/', '', $currentTime);
        $hour = substr($currentTime, 0, 2);
        $superSenha = $currentDate . $hour;

        return $superSenha;
    }

    public function logout()
    {
        if (session()->has('adm')) {
            session(['adm' => false]);
        }

        $nomeEmpresa = Session::get('empresa');

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('showLoginForm', ['empresa' => $nomeEmpresa]);
    }

    public function firstAcessView()
    {
        return view("firstAcess");
    }

    public function searchUser(Request $request)
    {
        $login = $request->get('login');
        $user = Usuario::where('login', $login)->first();

        if ($user) {
            if ($user->senha === null) {
                return view('firstAcess2', ['login' => $user->login]);
            } else {
                $loginError = session('loginError', 'Esse login já realizou o primeiro acesso');
                return view('firstAcess', compact('loginError'));
            }
        } else {
            $loginError = session('loginError', 'Usuário não encontrado');
            return view('firstAcess', compact('loginError'));
        }


    }

    public function firstAcessSubmit(Request $request)
    {
        $login = $request->get('login');
        $password = $request->get('password');
        $password2 = $request->get('password2');


        if ($password != $password2) {
            $loginError = session('loginError', 'Senhas não correspondem');
            return view('pages.firstAcess2', compact('loginError', 'login'));
        }

        $user = Usuario::where('login', $login)->first();

        $senhaCriptografada = md5($request->input('password'));

        $user->senha = $senhaCriptografada;
        $user->save();

        $successMessage = 'Senha cadastrada com sucesso!';

        session()->flash('loginError', $successMessage);

        $empresaId = $user->empresa_id;
        $empresa = Empresa::where('empresa_id', $empresaId)->first();
        $nomeF = $empresa->nome_fantasia;

        return redirect()->route('showLoginForm', ['empresa' => $nomeF]);

    }

}

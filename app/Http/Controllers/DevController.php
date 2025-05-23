<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Carbon\Carbon;


class DevController extends Controller
{
    public function entepriseDevView()
    {
        return view('enterpriseDev');   
    }

    public function saveEnterpriseDev(Request $request)
    {
        $nome = $request->input('nome');
        $nome_fantasia = $request->input('nome_fantasia');
        $cnpj = $request->input('cnpj');

        DB::beginTransaction();
        
        try {
            $empresa_id = Empresa::max('empresa_id') + 1;

            $Empresa = new Empresa();
            $Empresa->empresa_id = $empresa_id;
            $Empresa->nome = $nome;
            $Empresa->nome_fantasia = $nome_fantasia;
            $Empresa->cnpj = $cnpj;
          
            $Empresa->save();
    
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Empresa salva com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erro ao salvar empresa.']);
        }
    }

    public function userDevView()
    {
        $empresas = Empresa::all();
        return view('userDev', compact('empresas'));   
    }

    public function revDevView()
    {
        $empresas = Empresa::all();
        return view('revDev', compact('empresas'));   
    }

    public function saveUserDev(Request $request)
    {
        $nome = $request->input('nome');
        $login = $request->input('login');
        $empresa_id = $request->input('empresa_id');
        $tipo_usuario_id = $request->input('tipo_usuario_id');

        DB::beginTransaction();
        
        try {
            $usuarioUuid = (string) Str::uuid();
            $Usuario = new Usuario();
            $Usuario->usuario_id = $usuarioUuid;
            $Usuario->nome = $nome;
            $Usuario->login = $login;
            $Usuario->empresa_id = $empresa_id;
            $Usuario->tipo_usuario_id = $tipo_usuario_id ;
            $Usuario->ativo = true;
          
            $Usuario->save();
    
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Usuário salvo com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
             return response()->json(['success' => false, 'message' => 'Erro ao salvar usuário.']);
        }
    }

    public function createCalendar()
    {
        $empresas = Empresa::all();
        return view('createCalendar', compact('empresas'));   
    }

    public function newCalendarSave(Request $request)
    {
        $empresaId = $request->input('empresaId');
        $anoFinal = $request->input('ano_fim');
        $anoInicial = Carbon::now()->year;

        $feriados = [
            "01-01", // Confraternização Universal
            "03-04", // Carnaval
            "04-18", // Sexta-feira Santa
            "04-21", // Tiradentes
            "05-01", // Dia do Trabalho
            "06-19", // Corpus Christi
            "09-07", // Independência do Brasil
            "10-12", // Nossa Senhora Aparecida
            "11-02", // Finados
            "11-15", // Proclamação da República
            "12-25", // Natal
        ];

        $newEntries = [];

        for ($year = $anoInicial; $year <= $anoFinal; $year++) {
            $startDate = Carbon::create($year, 1, 1);
            $endDate = Carbon::create($year, 12, 31);

            $existingDates = DB::table('calendario')
                ->whereYear('data', $year)
                ->pluck('data')
                ->toArray();

            foreach (Carbon::parse($startDate)->daysUntil($endDate) as $date) {
                if (in_array($date->toDateString(), $existingDates)) {
                    continue; 
                }

                $isFeriado = in_array($date->format('m-d'), $feriados);

                $newEntries[] = [
                    'empresa_id' => $empresaId, 
                    'data' => $date->toDateString(),
                    'status' => !$isFeriado, 
                    'feriado' => $isFeriado,
                    'promocao' => false, 
                    'dia_semana' => $date->locale('pt_BR')->dayName, 
                ];
            }
        }

        DB::beginTransaction();

        try {
            if (!empty($newEntries)) {
                DB::table('calendario')->insert($newEntries);
            }

            $this->insertCalendarParameters($empresaId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Calendário para os anos selecionados populados com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar o calendário. Tente novamente.',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function insertCalendarParameters($empresaId)
    {
        $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo', 'feriado'];

        $parametros = [];
        foreach ($diasSemana as $dia) {
            $parametros[] = [
                'empresa_id' => $empresaId,
                'dia_semana' => $dia,
                'status' => true, 
            ];
        }

        DB::beginTransaction();

        try {
            DB::table('calendario_parametro')->insert($parametros);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
}

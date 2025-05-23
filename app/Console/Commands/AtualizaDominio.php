<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Interno;

class AtualizaDominio extends Command
{
    protected $signature = 'atualiza:dominio';
    protected $description = 'Atualiza o domínio na tabela interno';

    public function handle()
    {
        $urlBase = rtrim(env('APP_URL'), '/');

        $internoId = 1;

        Interno::updateOrCreate(
            ['interno_id' => $internoId],
            ['dominio' => $urlBase]
        );

        $this->info("Domínio atualizado para: {$urlBase} no interno_id {$internoId}");
    }
}

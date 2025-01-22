<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Calendario;
use Carbon\Carbon;

class DisablePastDays extends Command
{
    protected $signature = 'calendario:disable-past-days';
    protected $description = 'Desabilita os dias anteriores ao dia atual na tabela calendario';

    public function handle()
    {
        $hoje = Carbon::today();
        
        Calendario::where('data', '<', $hoje)->update(['status' => false]);

        $this->info('Dias passados desabilitados com sucesso.');
        return 0;
    }
}

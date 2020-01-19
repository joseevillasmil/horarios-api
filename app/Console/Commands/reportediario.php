<?php

namespace App\Console\Commands;

use App\Cita;
use App\Contenedor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class reportediario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reportediario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reporte de horas reservadas del día de hoy.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contenedores = Contenedor::get();
        foreach($contenedores as $contenedor) {
            $citas = Cita::whereContenedorId($contenedor->id)
                        ->whereDate('inicio', date('Y-m-d'))
                        ->whereHas('cliente')
                        ->get();
            if($citas->isNotEmpty()) {
                Mail::to($contenedor->correo_notificacion)->send(new \App\Mail\ReporteDiario($citas));
            }

        }
    }
}

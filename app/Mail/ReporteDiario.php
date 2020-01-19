<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReporteDiario extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    var $citas;
    public function __construct($citas)
    {
        $this->citas = $citas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Horas reservadas del día')
                    ->from(env('MAIL_FROM'), 'HORAS RESERVADAS')
                    ->view('mails.reporte-diario')
                    ->with(['citas' => $this->citas]);
    }
}

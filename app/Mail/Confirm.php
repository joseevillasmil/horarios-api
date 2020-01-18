<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Confirm extends Mailable
{
    use Queueable, SerializesModels;
    var $fecha;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Hora de atención reservada')
            ->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
            ->view('mails/confirm')
            ->with('fecha', $this->fecha);
    }
}

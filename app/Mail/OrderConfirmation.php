<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $mensajeSuccessFormateado;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $mensajeSuccessFormateado)
    {
        $this->order = $order;
        $this->mensajeSuccessFormateado = $mensajeSuccessFormateado;
    }

    public function build()
    {
        $pdfPath = storage_path('app/public/' . env('ORDER_PDF_FILENAME', 'SUBASTA PUBLICA  NRO 001-2024-EMILIMA-FOMUR-1ERA-CONVOC.pdf'));

        return $this->view('emails.order_confirmation')
                    ->subject(env('SUBJECT_MAIL', '[Confirmación de Pedido] SUBASTA PUBLICA  NRO 001-2024-EMILIMA-FOMUR-1ERA-CONVOCATORIA'))
                    ->with([
                        'order' => $this->order,
                        'mensajeSuccessFormateado' => $this->mensajeSuccessFormateado,
                    ])
                    ->attach($pdfPath)
                    ->withSwiftMessage(function ($message) {
                        $message->getHeaders()->addTextHeader('Content-Type', 'text/html; charset=UTF-8');
                    });
    }
}

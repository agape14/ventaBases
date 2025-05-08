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
        $orderItem = $this->order->orderItems->first();
        $product = optional($orderItem)->product;
        //$product = $this->order->orderItems->first()->product;

        $subject = $product->subject_mail ?? '[Confirmación de Pedido] Sin asunto';
        $pdfFilename = $product->order_pdf_filename ?? 'default.pdf';
        $pdfPath = storage_path('app/public/' . $pdfFilename);

        return $this->view('emails.order_confirmation')
                    ->subject($subject)
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

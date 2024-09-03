<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
</head>
<body>
    <h1>¡Gracias por tu compra!</h1>
    <p>Estimado(a) {{ $order->name }},</p>
    <p>Tu compra ha sido confirmada exitosamente. Aquí tienes los detalles de tu pedido:</p>

    <p>{!! $mensajeSuccessFormateado !!}</p>

    <p>Adjunto se te remite las bases, el comprobante de pago electrónico elegido será enviado posteriormente.</p>

    <p>Atentamente,<br>El equipo de {{ config('app.name') }}</p>
</body>
</html>

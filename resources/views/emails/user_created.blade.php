<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
    <h2>¡Hola {{ $name }}!</h2>
    <p>Te damos la bienvenida al sistema de ventas de bases de <strong>Emilima</strong>.</p>
    <p>Se ha creado una cuenta para ti. Aquí están tus credenciales de acceso:</p>

    <ul>
        <li><strong>Correo:</strong> {{ $email }}</li>
        <li><strong>Contraseña:</strong> {{ $passwordGenerado }}</li>
    </ul>

    <p>Por favor, ingresa al sistema para consultar tus compras y acceder a nuestros servicios.</p>
    <p><a href="{{ url('/') }}">Ir al sistema</a></p>

    {{-- <p>Recomendamos cambiar tu contraseña después del primer inicio de sesión.</p>--}}

    <p>Gracias por confiar en Emilima.</p>
</body>
</html>

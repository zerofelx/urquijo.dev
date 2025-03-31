<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? 'No proporcionado', FILTER_SANITIZE_STRING);
    $service = filter_var($_POST['service'], FILTER_SANITIZE_STRING);

    // Configurar el correo
    $to = "jesusurquijo.job@gmail.com"; // Tu correo de Zoho
    $subject = "Nuevo Freelance disponible - $service";

    // Crear el mensaje en HTML
    $message = "
    <html>
    <head>
        <title>Nuevo mensaje de contacto</title>
    </head>
    <body>
        <h2>Has recibido un nuevo mensaje de contacto</h2>
        <table>
            <tr><td><strong>Nombre:</strong></td><td>$name</td></tr>
            <tr><td><strong>Correo:</strong></td><td>$email</td></tr>
            <tr><td><strong>Teléfono:</strong></td><td>$phone</td></tr>
            <tr><td><strong>Servicio:</strong></td><td>$service</td></tr>
        </table>
    </body>
    </html>
    ";

    // Cabeceras para envío HTML y evitar spam
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: Formulario Web <contacto@urquijo.dev>',
        'Reply-To: ' . $name . ' <' . $email . '>',
        'X-Mailer: PHP/' . phpversion()
    );

    // Enviar el correo
    if(mail($to, $subject, $message, implode("\r\n", $headers))) {
        header("Location: /?success=true");
        exit();
    } else {
        header("Location: /?error=true");
        exit();
    }
}
?> 
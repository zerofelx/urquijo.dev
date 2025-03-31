<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? 'No proporcionado', FILTER_SANITIZE_STRING);
    $service = filter_var($_POST['service'], FILTER_SANITIZE_STRING);
    
    // Traducir el valor del servicio a español
    $serviceTranslations = [
        'web-development' => 'Desarrollo Web',
        'backend-development' => 'Desarrollo Backend',
        'api-integration' => 'Integración de APIs',
        'api-development' => 'Creación de APIs',
        'database-design' => 'Diseño de Bases de Datos',
        'server-management' => 'Gestión de Servidores'
    ];
    
    $serviceText = $serviceTranslations[$service] ?? $service;
    
    // Configurar el correo
    $to = "jesusurquijo.job@gmail.com";
    $subject = "Nuevo Freelance disponible: $serviceText";

    // Crear el mensaje en HTML con estilos modernos
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Un nuevo contrato está disponible</title>
    </head>
    <body style='margin: 0; padding: 0; background-color: #f6f6f6; font-family: Arial, sans-serif;'>
        <table role='presentation' width='100%' style='width: 100%; background-color: #f6f6f6; padding: 20px;'>
            <tr>
                <td align='center'>
                    <table role='presentation' style='max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                        <!-- Header -->
                        <tr>
                            <td style='background-color: #8b5cf6; padding: 30px 40px; text-align: center;'>
                                <h1 style='color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;'>
                                    Nuevo contrato disponible
                                </h1>
                            </td>
                        </tr>
                        
                        <!-- Content -->
                        <tr>
                            <td style='padding: 40px;'>
                                <table role='presentation' width='100%'>
                                    <tr>
                                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>
                                            <strong style='color: #8b5cf6; display: inline-block; width: 120px;'>Nombre:</strong>
                                            <span style='color: #333333;'>$name</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>
                                            <strong style='color: #8b5cf6; display: inline-block; width: 120px;'>Email:</strong>
                                            <span style='color: #333333;'>$email</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>
                                            <strong style='color: #8b5cf6; display: inline-block; width: 120px;'>Teléfono:</strong>
                                            <span style='color: #333333;'>$phone</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>
                                            <strong style='color: #8b5cf6; display: inline-block; width: 120px;'>Servicio:</strong>
                                            <span style='color: #333333;'>$serviceText</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!-- Footer -->
                        <tr>
                            <td style='background-color: #f8f7ff; padding: 20px 40px; text-align: center; color: #666666; font-size: 14px;'>
                                <p style='margin: 0;'>Este mensaje fue enviado desde el formulario de contacto de urquijo.dev</p>
                            </td>
                        </tr>
                    </table>
                    
                    <!-- Timestamp -->
                    <table role='presentation' style='max-width: 600px; width: 100%;'>
                        <tr>
                            <td style='padding: 20px; text-align: center; color: #999999; font-size: 12px;'>
                                Mensaje recibido el " . date('d/m/Y') . " a las " . date('H:i') . "
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    ";

    // Cabeceras para envío HTML
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
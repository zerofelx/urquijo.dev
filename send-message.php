<?php
header('Content-Type: application/json; charset=UTF-8');

function respond(int $status, bool $success, string $message): void
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, false, 'Método no permitido');
}

// Honeypot: real visitors never see this field. Pretend it worked so bots move on.
if (!empty($_POST['website'])) {
    respond(200, true, 'Mensaje enviado correctamente');
}

// Single-line text: trim, drop control characters (blocks header injection), cap length.
function clean_line($value, int $max = 200): string
{
    $value = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', (string) $value);
    return mb_substr(trim($value), 0, $max);
}

$name = clean_line($_POST['name'] ?? '', 100);
$email = clean_line($_POST['email'] ?? '', 254);
$phone = clean_line($_POST['phone'] ?? '', 40);
$service = clean_line($_POST['service'] ?? '', 50);

// Keys match src/assets/json/areas.json (plus "other").
$serviceTranslations = [
    'automation' => 'Bots y automatización',
    'apis' => 'APIs e integraciones',
    'servers' => 'Servidores Linux y VPS',
    'web' => 'Desarrollo web',
    'other' => 'Otro / no estoy seguro',
];

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !isset($serviceTranslations[$service])) {
    respond(422, false, 'Datos inválidos');
}

$e = fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

$serviceText = $serviceTranslations[$service];
$phoneText = $phone !== '' ? $phone : 'No proporcionado';

$to = 'jesusurquijo.job@gmail.com';
$subject = '=?UTF-8?B?' . base64_encode("Nuevo Freelance disponible: $serviceText") . '?=';

$rows = '';
foreach (['Nombre' => $name, 'Email' => $email, 'Teléfono' => $phoneText, 'Servicio' => $serviceText] as $label => $value) {
    $rows .= "
                                    <tr>
                                        <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>
                                            <strong style='color: #8b5cf6; display: inline-block; width: 120px;'>{$label}:</strong>
                                            <span style='color: #333333;'>{$e($value)}</span>
                                        </td>
                                    </tr>";
}

$date = date('d/m/Y');
$time = date('H:i');

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
                    <tr>
                        <td style='background-color: #8b5cf6; padding: 30px 40px; text-align: center;'>
                            <h1 style='color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;'>Nuevo contrato disponible</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 40px;'>
                            <table role='presentation' width='100%'>{$rows}
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style='background-color: #f8f7ff; padding: 20px 40px; text-align: center; color: #666666; font-size: 14px;'>
                            <p style='margin: 0;'>Este mensaje fue enviado desde el formulario de contacto de urquijo.dev</p>
                        </td>
                    </tr>
                </table>
                <table role='presentation' style='max-width: 600px; width: 100%;'>
                    <tr>
                        <td style='padding: 20px; text-align: center; color: #999999; font-size: 12px;'>
                            Mensaje recibido el {$date} a las {$time}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
";

$replyName = '=?UTF-8?B?' . base64_encode($name) . '?=';
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: Formulario Web <contacto@urquijo.dev>',
    "Reply-To: {$replyName} <{$email}>",
];

if (mail($to, $subject, $message, implode("\r\n", $headers))) {
    respond(200, true, 'Mensaje enviado correctamente');
}

respond(500, false, 'Error al enviar el mensaje');

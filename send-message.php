<?php
header('Content-Type: application/json; charset=UTF-8');

function respond(int $status, bool $success, string $message): void
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

/*
 * Mail settings. Each one comes from an environment variable when the server
 * provides it, otherwise from a .env file one level ABOVE the web root
 * (next to public_html, never inside it), otherwise from the default below.
 * This repo is public: the password lives only in that .env on the server.
 * See .env.example in the source repo.
 */
const ENV_FILE = __DIR__ . '/../.env';

function load_config(): array
{
    $defaults = [
        'SMTP_HOST' => 'smtp.hostinger.com',
        'SMTP_PORT' => '465',
        'SMTP_USER' => 'forms@urquijo.dev',
        'SMTP_PASS' => '',
        'MAIL_TO' => 'jesusurquijo.job@gmail.com',
    ];

    // RAW keeps the password as written (no "true"/"null" conversions); wrap it in
    // double quotes in the file if it contains ; # = or spaces.
    $file = is_readable(ENV_FILE) ? (parse_ini_file(ENV_FILE, false, INI_SCANNER_RAW) ?: []) : [];

    $config = [];
    foreach ($defaults as $key => $default) {
        $value = getenv($key);
        $config[$key] = (string) ($value !== false && $value !== '' ? $value : ($file[$key] ?? $default));
    }
    return $config;
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
    $value = trim(preg_replace('/[\x00-\x1F\x7F]+/u', ' ', (string) $value));
    // mbstring is not enabled on every host.
    return function_exists('mb_substr') ? mb_substr($value, 0, $max) : substr($value, 0, $max);
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

$subject = "Nuevo Freelance disponible: $serviceText";

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

$config = load_config();
if ($config['SMTP_PASS'] === '') {
    error_log('send-message.php: falta SMTP_PASS (ni variable de entorno ni ' . ENV_FILE . ')');
    respond(500, false, 'Error al enviar el mensaje');
}

require __DIR__ . '/lib/phpmailer/Exception.php';
require __DIR__ . '/lib/phpmailer/PHPMailer.php';
require __DIR__ . '/lib/phpmailer/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Authenticated SMTP, the same route as a mail client: the message leaves
    // through the mailbox's server, so SPF and DKIM line up with urquijo.dev.
    $mail->isSMTP();
    $mail->Host = $config['SMTP_HOST'];
    $mail->Port = (int) $config['SMTP_PORT'];
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    $mail->SMTPAuth = true;
    $mail->Username = $config['SMTP_USER'];
    $mail->Password = $config['SMTP_PASS'];
    $mail->Timeout = 15;

    $mail->CharSet = PHPMailer\PHPMailer\PHPMailer::CHARSET_UTF8;
    // The sender has to be the mailbox we log in with.
    $mail->setFrom($config['SMTP_USER'], 'Formulario urquijo.dev');
    $mail->addAddress($config['MAIL_TO']);
    $mail->addReplyTo($email, $name);

    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $message;
    $mail->AltBody = "Nombre: {$name}\nEmail: {$email}\nTeléfono: {$phoneText}\nServicio: {$serviceText}";

    $mail->send();
    respond(200, true, 'Mensaje enviado correctamente');
} catch (PHPMailer\PHPMailer\Exception $error) {
    // Leave the real cause in the hosting's PHP error log, never in the response.
    error_log('send-message.php: SMTP falló: ' . $mail->ErrorInfo);
    respond(500, false, 'Error al enviar el mensaje');
}

<?php
require 'vendor/autoload.php';
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function checkMetaName($url) {
    $html = file_get_contents($url);
    if ($html !== false) {
        return strpos($html, '<meta name="robots" content="NOINDEX') !== false;
    }
    return false;
}

function check401($url) {
    $headers = get_headers($url, 1);
    if ($headers !== false && is_array($headers) && isset($headers[0])) {
        return strpos($headers[0], '401') === false;
    }
    return false;
}

$folder = 'var/';
$directories = scandir($folder);
foreach ($directories as $dir) {
    if ($dir != '.' && $dir != '..') {
        if (strpos($dir, '.') !== false) {
            $url = 'http://' . $dir;
            $sites[] = $url;
        }
    }
}
 

$problematicSites = [];

foreach ($sites as $site) {
    $url = trim($site);
    if (!check401($url) || !checkMetaName($url)) {
        $problematicSites[] = $url;
    }
}

if (!empty($problematicSites)) {
    $message = "Список сайтів, що не відповідають критеріям:\n";
    foreach ($problematicSites as $site) {
        $message .= $site . "\n";
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;


        $mail->setFrom(SMTP_USERNAME, 'Mailer');
        $mail->addAddress('vit.melnyk51@gmail.com');

        $mail->isHTML(false);
        $mail->Subject = 'Sites';
        $mail->Body = $message;

        $mail->send();
        echo 'Повідомлення відправлено успішно!';
    } catch (Exception $e) {
        echo "Повідомлення не вдалося відправити. Причина: {$mail->ErrorInfo}";
    }
}
?>


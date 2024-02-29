<?php
require 'vendor/autoload.php';

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

$sites = file("sites.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 

$emailAddress = "vit.melnyk51@gmail.com";

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
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'vit.melnyk51@gmail.com'; 
        $mail->Password = 'xiie ti';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('vit.melnyk51@gmail.com', 'Mailer');
        $mail->addAddress($emailAddress);

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

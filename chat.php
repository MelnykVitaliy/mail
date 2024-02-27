<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// function checkMetaName($url) {
//     $html = file_get_contents($url);
//     if ($html !== false) {
//         return strpos($html, '<meta name="robots" content="NOINDEX') !== false;
//     }
//     return false;
// }

function check401($url) {
    $headers = get_headers($url, 1);
    if ($headers !== false && is_array($headers) && isset($headers[0])) {
        return strpos($headers[0], 'HTTP/1.1 200') !== false;
    }
    return false;
}

$sites = file("sites.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 

$emailAddress = "vit.melnyk51@gmail.com";

$problematicSites = [];

foreach ($sites as $site) {
    $url = trim($site);
    if (!check401($url)) {
        $problematicSites[] = $url;
    }
}

echo "<pre>";
print_r($problematicSites);
echo "<\pre>";
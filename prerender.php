<?php
// Script Proxy para Prerender.io
// Arquivo responsável por interceptar a visita do bot e pedir a versão renderizada para o Prerender.io

$prerenderToken = 'Va3s7hBnP19Zfpnc7XkS';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// 1. Prevenção de Loop de Testes do Prerender
if (strpos($userAgent, 'prerender') !== false && strpos($userAgent, 'Prerender Proxy') !== false) {
    http_response_code(200);
    echo file_get_contents(__DIR__ . '/index.html');
    exit;
}

// 2. Definindo a URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https';
}

$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$originalUrl = $protocol . '://' . $host . $uri;

// 3. Montando a URL da API Prerender
$prerenderApiUrl = 'https://service.prerender.io/' . $originalUrl;

// 4. Executando Curl
$ch = curl_init($prerenderApiUrl);
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent . ' Prerender Proxy');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Prerender-Token: ' . $prerenderToken
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// MUITO IMPORTANTE: Na hostinger, FOLLOWLOCATION as vezes falha restrito por open_basedir
@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Forçando sucesso de SSL em casos de proxy

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    // Falha total do Curl (Ex: timeout ou bloqueio interno do firewall do Hostinger)
    http_response_code(200);
    header('Content-Type: text/html; charset=utf-8');
    header('X-Prerender-Fallback: true'); // Flag para sabermos que o fallback rodou
    echo file_get_contents(__DIR__ . '/index.html');
} else {
    // Retornamos exatamente o Código do Prerender.io
    // Evitando devolver status obscuros durante a fase de teste (força 200 se 404 pra home)
    if ($httpCode >= 400 && $uri === '/') {
        $httpCode = 200;
    }
    
    http_response_code($httpCode);
    header('Content-Type: text/html; charset=utf-8');
    echo $response;
}

curl_close($ch);
?>

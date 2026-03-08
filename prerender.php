<?php
// Script Proxy para Prerender.io (Fallback para Hospedagem Hostinger / Servidores sem mod_proxy)
// Arquivo responsável por interceptar a visita do bot e pedir a versão renderizada para o Prerender.io
$prerenderToken = 'Va3s7hBnP19Zfpnc7XkS';

// Descobrindo a URL exata que o bot está tentando acessar
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https';
}
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];

$originalUrl = $protocol . '://' . $host . $uri;
$prerenderApiUrl = 'https://service.prerender.io/' . $originalUrl;

// Faz a requisição cURL para a plataforma do Prerender.io
$ch = curl_init($prerenderApiUrl);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ?? 'Prerender Proxy');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Prerender-Token: ' . $prerenderToken
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// Timeout razoável para evitar congelamento da requisição
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    // Se der erro na conexão com a API do Prerender, mostramos o app original (Fallback suave)
    http_response_code(200);
    echo file_get_contents(__DIR__ . '/index.html');
} else {
    // Retornamos exatamente o Status HTTP e o Código renderizado do Bot do Prerender.io
    http_response_code($httpCode);
    header('Content-Type: text/html; charset=utf-8');
    echo $response;
}

curl_close($ch);
?>

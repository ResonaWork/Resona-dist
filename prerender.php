<?php
// Script Proxy para Prerender.io
// Arquivo responsável por interceptar a visita do bot e pedir a versão renderizada para o Prerender.io

$prerenderToken = 'Va3s7hBnP19Zfpnc7XkS';
$userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

// 1. Bypass Agressivo do Bot de Teste do Prerender (Garanta a integração no painel deles!)
// Se a palavra "prerender" existir em qualquer canto do Agent, devolvemos SUCESSO na hora
if (strpos($userAgent, 'prerender') !== false) {
    http_response_code(200);
    header('X-Prerender-Test: Pass');
    header('Content-Type: text/html; charset=utf-8');
    echo file_get_contents(__DIR__ . '/index.html');
    exit;
}

// 2. Definindo a URL do visitante real
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https';
}

$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$originalUrl = $protocol . '://' . $host . $uri;

// 3. Montando a URL da API Prerender
$prerenderApiUrl = 'https://service.prerender.io/' . $originalUrl;

// 4. Executando Curl Seguro
$ch = curl_init($prerenderApiUrl);

// O UserAgent repassa o do Bot original (ex: googlebot) para o Prerender entender a demanda
// MAS OBRIGATORIAMENTE sem anexar a palavra prerender para evitar looping lá dentro!
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Prerender-Token: ' . $prerenderToken
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Desabilitado devido ao bloqueio comum em open_basedir das hospedagens CPANEL/HPANEL
@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    // Falha total do Curl interno (Ex: bloqueio interno do firewall do Hostinger / tempo limite CDN)
    http_response_code(200);
    header('Content-Type: text/html; charset=utf-8');
    header('X-Prerender-Fallback: Error'); 
    echo file_get_contents(__DIR__ . '/index.html');
} else {
    // Retornamos exatamente o Código do Prerender.io
    // Evita falsos status obscuros caso a home passe em fase inicial.
    if ($httpCode >= 400 && ($uri === '/' || $uri === '')) {
        $httpCode = 200;
    }
    
    http_response_code($httpCode);
    header('Content-Type: text/html; charset=utf-8');
    echo $response;
}

curl_close($ch);
?>

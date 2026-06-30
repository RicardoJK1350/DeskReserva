<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Sao_Paulo');

define('BASE_PATH', str_replace('\\', '/', realpath(__DIR__)));

$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$projeto = str_replace($_SERVER['DOCUMENT_ROOT'], '', BASE_PATH);
define('BASE_URL', $protocolo . "://" . $host . $projeto);

function css($arquivo)
{
    return BASE_URL . "/views/assets/css/" . $arquivo . ".css";
}

function js($arquivo)
{
    return BASE_URL . "/views/assets/js/" . $arquivo . ".js";
}

function img($arquivo)
{
    return BASE_URL . "/views/assets/img/" . $arquivo;
}

function e($valor)
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

define('CLASSES_PATH', BASE_PATH . '/classes');
define('VIEWS_PATH', BASE_PATH . '/views');
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('INCLUDES_PATH', BASE_PATH . '/views/includes');

spl_autoload_register(function ($nomeClasse) {
    $diretorios = [
        CLASSES_PATH . '/',
        CONTROLLERS_PATH . '/'
    ];
    foreach ($diretorios as $diretorio) {
        $arquivo = $diretorio . $nomeClasse . '.php';

        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }
});


function render_view($viewName, $data = [], $layout = true)
{
    extract($data);

    $viewFile = VIEWS_PATH . '/' . $viewName . '.php';

    if (file_exists($viewFile)) {
        if ($layout) {
            require_once INCLUDES_PATH . '/header.php';
        }
        require_once $viewFile;
        if ($layout) {
            require_once INCLUDES_PATH . '/footer.php';
        }
    } else {
        if ($viewName === '404') {
            die("Erro crítico: A view de erro '404.php' não foi encontrada na pasta de views.");
        }

        render_view('404', ['slug' => $viewName], $layout);
        exit;
    }
}

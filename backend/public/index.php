<?php
declare(strict_types=1);

const ENVIRONMENT = 'production'; // or 'development'

if (ENVIRONMENT === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

const ALLOWED_ORIGINS = [
    'https://scandiweb-senior-test.test',
    'https://scandiweb.phoenixtechs.tech',
    'http://localhost',
    "http://127.0.0.1",
];


$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, ALLOWED_ORIGINS)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Access-Control-Allow-Credentials: true');
}


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}


ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/logs/error.log');


set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno]: $errstr in $errfile on line $errline");
    return true;
});


set_exception_handler(function($exception) {
    error_log("Uncaught Exception: " . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
    exit();
});


session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);


define('ROOT', dirname(__DIR__));


$scheme = $_SERVER['REQUEST_SCHEME']
    ?? ($_SERVER['HTTP_X_FORWARDED_PROTO']
        ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'));

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseDir = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;

define('BASE_URL', rtrim("$scheme://$host", '/') . $baseDir . '/');


try {
    require_once ROOT . '/vendor/autoload.php';
    require_once ROOT . '/config/app.php';
    require_once ROOT . '/routes/api.php';
} catch (Throwable $e) {
    error_log("Fatal Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server Configuration Error']);
    exit();
}


register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        error_log("Fatal Error: " . $error['message']);
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
});
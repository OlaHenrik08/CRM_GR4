
<?php

mysqli_report(MYSQLI_REPORT_OFF);

$servername = getenv('olahenrik-crm-sql-olahenrik08-535f.j.aivencloud.com');
$username   = getenv('avnadmin');
$password   = getenv('AVNS_qUezy1tyRwpTUzLO8j3');
$db         = getenv('defaultdb');
$port       = (int) (getenv('26982') ?: 3306);

$conn = mysqli_init();

mysqli_ssl_set(
    $conn,
    null,
    null,
    __DIR__ . '/ca.pem',
    null,
    null
);

$connected = $conn->real_connect(
    $servername,
    $username,
    $password,
    $db,
    $port,
    null,
    MYSQLI_CLIENT_SSL
);

if (!$connected) {
    error_log('Database connection failed: ' . $conn->connect_error);
    http_response_code(500);
    exit('Kunne ikke koble til databasen.');
}

$conn->set_charset('utf8mb4');

// Hjelpefunksjon for å unngå XSS
function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
?>

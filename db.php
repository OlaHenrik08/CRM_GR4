
<?php

mysqli_report(MYSQLI_REPORT_OFF);

// Hent verdier fra Render Environment Variables
$servername = getenv('olahenrik-crm-sql-olahenrik08-535f.j.aivencloud.com');
$username   = getenv('avnadmin');
$password   = getenv('AVNS_qUezy1tyRwpTUzLO8j3');
$db         = getenv('defaultdb');
$port       = (int) (getenv('26982') ?: 3306);

// Kontroller at alle nødvendige verdier finnes
if (!$servername || !$username || !$password || !$db) {
    error_log('Missing database environment variables');
    http_response_code(500);
    exit('Database configuration is incomplete.');
}

// Opprett databaseforbindelse
$conn = mysqli_init();

// Sett opp SSL med Aiven CA-sertifikat
mysqli_ssl_set(
    $conn,
    null,
    null,
    '/etc/ssl/certs/aiven-ca.pem',
    null,
    null
);

// Koble til Aiven
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

// Sett tegnsett
$conn->set_charset('utf8mb4');

// Hjelpefunksjon for å unngå XSS
function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
?>

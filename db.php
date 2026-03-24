<?php
$servername = "localhost:3306";
$username = "db_im24ola29112";
$password = "e6Va%et9raL2G?yk";
$db = "db_im24ola29112";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Kunne ikke koble til databasen: " . $conn->connect_error);
}

// Hjelpefunksjon for å unngå XSS - brukes som h($variabel) i alle filer
function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<?php
// $host = "localhost";
// $user = "root";
// $password = "";
// $db = "crm_gr4";

// $conn = new mysqli($host, $user, $password, $db);

// if ($conn->connect_error) {
//     die("Kunne ikke koble til databasen: " . $conn->connect_error);
// }

// // Hjelpefunksjon for å unngå XSS - brukes som h($variabel) i alle filer
// function h($s) {
//     return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
// }
?>
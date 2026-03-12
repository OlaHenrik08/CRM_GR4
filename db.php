<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "crm_gr4";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Kunne ikke koble til databasen: " . $conn->connect_error);
}
?>
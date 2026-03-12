<?php
session_start();
include 'db.php';

// Sjekker om brukeren er logget inn
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$kunde_id = $_GET['kunde_id'];

// Sletter kontaktpersonen fra databasen
$sql = "DELETE FROM kontaktperson WHERE kontakt_id = $id";
$conn->query($sql);

// Sender tilbake til kundedetaljsiden
header("Location: kunde_detalj.php?id=$kunde_id");
exit();
?>
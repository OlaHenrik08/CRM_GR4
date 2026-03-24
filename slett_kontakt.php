<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
$kunde_id = isset($_GET['kunde_id']) && is_numeric($_GET['kunde_id']) ? (int)$_GET['kunde_id'] : null;

if (!$id) {
    header("Location: kontaktpersoner.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM kontaktperson WHERE kontakt_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

if ($kunde_id) {
    header("Location: kunde_detalj.php?id=$kunde_id");
} else {
    header("Location: kontaktpersoner.php");
}
exit();
?>
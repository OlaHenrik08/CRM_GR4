<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM kunde WHERE kunde_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header("Location: index.php");
exit();
?>
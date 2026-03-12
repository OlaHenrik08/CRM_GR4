<?php
session_start();
include "db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$username);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 1){

$user = $result->fetch_assoc();

if(password_verify($password,$user["password"])){

$_SESSION["user"] = $user["username"];

header("Location: index.php");
exit();

}else{
echo "Feil passord";
}

}else{
echo "Bruker finnes ikke";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logg inn</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <H1>Kundedatabase</H1>

    <h1>Logg inn</h1>

    <form id="logg-inn-form" method="POST" action="logginn.php">
        <label id="brukernavn" for="username">Brukernavn:</label>
        <input type="text" id="brukernavn" name="username" required><br>

        <label id="passord" for="password">Passord:</label>
        <input type="password" id="passord" name="password" required><br>

        <button type="submit" name="bekreft">Logg inn</button>

        <p>Har du ikke bruker? <a href="register.php">Registrer deg</a></p>
    </form>

</body>
</html>
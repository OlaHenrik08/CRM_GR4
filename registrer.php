<?php
include "db.php";

if(isset($_POST['bekreft'])){

$fornavn = $_POST['fornavn'];
$etternavn = $_POST['etternavn'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (fornavn, etternavn, username, password)
VALUES ('$fornavn','$etternavn','$username','$password')";

if($conn->query($sql) === TRUE){

header("Location: login.php");
exit();

}else{
echo "Noe gikk galt";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrer deg</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<h1>Kundedatabase</h1>

<h1>Registrer deg</h1>

<form id="logg-inn-form" method="POST">

<label for="fornavn">Fornavn:</label>
<input type="text" name="fornavn" required><br>

<label for="etternavn">Etternavn:</label>
<input type="text" name="etternavn" required><br>

<label for="username">Brukernavn:</label>
<input type="text" name="username" required><br>

<label for="password">Passord:</label>
<input type="password" name="password" required><br>

<button type="submit" name="bekreft">Registrer</button>

<p>Har du allerede bruker? <a href="login.php">Logg inn</a></p>

</form>

</body>
</html>
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
<html lang="no">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrer deg</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="main-content">

<div class="topbar">
<div>
<h1>Kundedatabase</h1>
<p class="subtitle">Opprett ny bruker</p>
</div>
</div>

<section class="table-section" style="max-width:400px; margin:auto;">

<form method="POST">

<label>Fornavn</label>
<input type="text" name="fornavn" required>

<br><br>

<label>Etternavn</label>
<input type="text" name="etternavn" required>

<br><br>

<label>Brukernavn</label>
<input type="text" name="username" required>

<br><br>

<label>Passord</label>
<input type="password" name="password" required>

<br><br>

<button type="submit" name="bekreft" class="btn primary">Registrer</button>

<p style="margin-top:15px;">
Har du allerede bruker? <a href="login.php">Logg inn</a>
</p>

</form>

</section>

</div>

</body>
</html>
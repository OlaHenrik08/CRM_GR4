<?php
session_start(); // Starter en session for å kunne lagre innlogget bruker
include "db.php"; // Kobler til databasen

$feil = ""; // Variabel for feilmelding

// Sjekker om skjemaet er sendt (POST-request)
if($_SERVER["REQUEST_METHOD"] == "POST"){

// Henter brukernavn og passord fra skjemaet
    $username = $_POST["username"];
    $password = $_POST["password"];

// Lager en SQL-spørring med prepared statement (sikker mot SQL-injection)
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

// Henter resultatet
    $result = $stmt->get_result();

// Sjekker om brukeren finnes (1 treff)
    if($result->num_rows == 1){

// Henter brukerdata som array
        $user = $result->fetch_assoc();

// Sjekker om passordet stemmer med det krypterte passordet i databasen
        if(password_verify($password, $user["password"])){

// Lagrer brukeren i session (brukeren er nå logget inn)
            $_SESSION["user"] = $user["username"];
            header("Location: index.php");
            exit();

        } else {
// Feilmelding hvis passordet er feil
            $feil = "Feil passord";
        }

    } else {
// Feilmelding hvis brukeren ikke finnes
        $feil = "Bruker finnes ikke";
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logg inn - CRM Gruppe 4</title>
  <link rel="stylesheet" href="assets/style.css" />
  <script src="assets/script.js"></script>
</head>
<body>

<div class="main-content">

  <div class="topbar">
    <div>
      <h1>Kundedatabase</h1>
      <p class="subtitle">Logg inn i CRM-systemet</p>
    </div>
  </div>

  <section class="table-section auth-section">

    <?php if($feil){ ?>
<!-- Viser feilmelding hvis noe gikk galt -->
      <p class="error"><?php echo $feil; ?></p>
    <?php } ?>

<!-- Innloggingsskjema -->
    <form method="POST" action="login.php">

      <div class="form-row">
        <label for="username">Brukernavn</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-row">
        <label for="password">Passord</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-row">
<!-- Knapp for å logge inn -->
        <button type="submit" class="btn primary">Logg inn</button>
      </div>

      <p class="auth-link">
<!-- Link til registreringsside -->
        Har du ikke bruker? <a href="registrer.php">Registrer deg</a>
      </p>

    </form>

  </section>

</div>

</body>
</html>
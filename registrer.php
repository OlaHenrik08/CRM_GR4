<?php
include "db.php"; // Kobler til databasen

$feil = ""; // Variabel for å lagre feilmelding

// Sjekker om skjemaet er sendt (POST-request)
if($_SERVER["REQUEST_METHOD"] == "POST"){

// Henter verdier fra skjemaet
    $fornavn   = $_POST['fornavn'];
    $etternavn = $_POST['etternavn'];
    $username  = $_POST['username'];

// Krypterer passordet før lagring
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

// SQL-spørring for å legge inn ny bruker i databasen
    $sql = "INSERT INTO users (fornavn, etternavn, username, password)
            VALUES ('$fornavn', '$etternavn', '$username', '$password')";

// Kjører spørringen og sjekker om det gikk bra
    if($conn->query($sql) === TRUE){
// Sender brukeren videre til login-siden etter vellykket registrering
        header("Location: login.php");
        exit();
    } else {
// Setter feilmelding hvis noe gikk galt (f.eks. brukernavn allerede i bruk)
        $feil = "Noe gikk galt. Brukernavnet er kanskje allerede i bruk.";
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrer deg - CRM Gruppe 4</title>
  <link rel="stylesheet" href="assets/style.css" />
  <script src="assets/script.js"></script>
</head>
<body>

<div class="main-content">

  <div class="topbar">
    <div>
      <h1>Kundedatabase</h1>
      <p class="subtitle">Opprett ny bruker</p>
    </div>
  </div>

  <section class="table-section auth-section">

    <?php if($feil){ ?>
<!-- Viser feilmelding hvis noe gikk galt -->
      <p class="error"><?php echo $feil; ?></p>
    <?php } ?>

<!-- Registreringsskjema -->
    <form method="POST" action="registrer.php">

      <div class="form-row">
        <label for="fornavn">Fornavn</label>
        <input type="text" id="fornavn" name="fornavn" required>
      </div>

      <div class="form-row">
        <label for="etternavn">Etternavn</label>
        <input type="text" id="etternavn" name="etternavn" required>
      </div>

      <div class="form-row">
        <label for="username">Brukernavn</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-row">
        <label for="password">Passord</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-row">
<!-- Knapp for å sende inn skjemaet -->
        <button type="submit" class="btn primary">Registrer</button>
      </div>

      <p class="auth-link">
<!-- Link til login-side -->
        Har du allerede bruker? <a href="login.php">Logg inn</a>
      </p>

    </form>

  </section>

</div>

</body>
</html>
<?php
include "db.php";

$feil = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $fornavn   = $_POST['fornavn'];
    $etternavn = $_POST['etternavn'];
    $username  = $_POST['username'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fornavn, etternavn, username, password)
            VALUES ('$fornavn', '$etternavn', '$username', '$password')";

    if($conn->query($sql) === TRUE){
        header("Location: login.php");
        exit();
    } else {
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

  <section class="table-section auth-section">

    <?php if($feil){ ?>
      <p class="error"><?php echo $feil; ?></p>
    <?php } ?>

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
        <button type="submit" class="btn primary">Registrer</button>
      </div>

      <p class="auth-link">
        Har du allerede bruker? <a href="login.php">Logg inn</a>
      </p>

    </form>

  </section>

</div>

</body>
</html>
<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Logg inn – CRM</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="login-page">

<div class="login-box">
  <div class="login-logo">CRM<span class="logo-dot">.</span></div>
  <p class="login-tagline">Logg inn for å fortsette</p>

  <!-- Feilmelding fra Celine sin PHP-kode vises her -->
  <?php if (!empty($feil)) { ?>
    <div class="alert error"><?php echo htmlspecialchars($feil); ?></div>
  <?php } ?>

  <!-- Skjemaet sender brukernavn og passord til login.php -->
  <form method="POST" action="login.php">
    <div class="form-group">
      <label for="brukernavn">Brukernavn</label>
      <input type="text" id="brukernavn" name="brukernavn" required autofocus />
    </div>
    <div class="form-group">
      <label for="passord">Passord</label>
      <input type="password" id="passord" name="passord" required />
    </div>
    <button type="submit" class="btn primary btn-full">Logg inn</button>
  </form>

  <p class="login-link">
    Har du ikke konto? <a href="registrer.php">Registrer deg</a>
  </p>
</div>

</body>
</html>

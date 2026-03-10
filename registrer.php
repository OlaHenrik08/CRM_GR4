<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrer – CRM</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="login-page">

<div class="login-box">
  <div class="login-logo">CRM<span class="logo-dot">.</span></div>
  <p class="login-tagline">Opprett ny bruker</p>

  <!-- Feil- og suksessmelding fra Celine sin PHP-kode vises her -->
  <?php if (!empty($feil)) { ?>
    <div class="alert error"><?php echo htmlspecialchars($feil); ?></div>
  <?php } ?>
  <?php if (!empty($suksess)) { ?>
    <div class="alert success"><?php echo $suksess; ?></div>
  <?php } ?>

  <!-- Skjemaet sender data til registrer.php -->
  <form method="POST" action="registrer.php">
    <div class="form-group">
      <label for="brukernavn">Brukernavn</label>
      <input type="text" id="brukernavn" name="brukernavn" required autofocus />
    </div>
    <div class="form-group">
      <label for="passord">Passord</label>
      <input type="password" id="passord" name="passord" required />
    </div>
    <div class="form-group">
      <label for="gjenta_passord">Gjenta passord</label>
      <input type="password" id="gjenta_passord" name="gjenta_passord" required />
    </div>
    <button type="submit" class="btn primary btn-full">Registrer</button>
  </form>

  <p class="login-link">
    Har du allerede konto? <a href="login.php">Logg inn</a>
  </p>
</div>

</body>
</html>

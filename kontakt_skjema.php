<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kontaktperson – CRM</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="app">

  <aside class="sidebar">
    <div class="logo">CRM<span class="logo-dot">.</span></div>
    <nav>
      <ul>
        <li><a href="index.php">Kunder</a></li>
        <li><a href="kontaktpersoner.php">Kontaktpersoner</a></li>
        <li><a href="logout.php">Logg ut</a></li>
      </ul>
    </nav>
    <div class="sidebar-footer">Gruppe 4</div>
  </aside>

  <main class="main-content">

    <div class="topbar">
      <div>
        <h1><?php echo $redigerer ? 'Rediger kontaktperson' : 'Ny kontaktperson'; ?></h1>
        <p class="subtitle">
          <?php echo $redigerer ? 'Oppdater informasjon om kontaktpersonen' : 'Legg til kontaktperson for kunden'; ?>
        </p>
      </div>
      <!-- Tilbake-knappen går til kundens detaljside -->
      <a href="kunde_detalj.php?id=<?php echo $kunde_id; ?>" class="btn secondary">← Tilbake</a>
    </div>

    <?php if (!empty($feil)) { ?>
      <div class="alert error"><?php echo htmlspecialchars($feil); ?></div>
    <?php } ?>

    <div class="form-card">
      <form method="POST" action="kontakt_skjema.php?kunde_id=<?php echo $kunde_id; ?><?php echo $redigerer ? '&id=' . $kp['kontakt_id'] : ''; ?>">

        <!-- Skjult felt som forteller PHP hvilken kunde kontaktpersonen tilhører -->
        <input type="hidden" name="kunde_id" value="<?php echo $kunde_id; ?>" />

        <div class="form-row">
          <div class="form-group">
            <label for="fornavn">Fornavn <span class="required">*</span></label>
            <input
              type="text"
              id="fornavn"
              name="fornavn"
              value="<?php echo htmlspecialchars($kp['fornavn'] ?? ''); ?>"
              required
            />
          </div>
          <div class="form-group">
            <label for="etternavn">Etternavn <span class="required">*</span></label>
            <input
              type="text"
              id="etternavn"
              name="etternavn"
              value="<?php echo htmlspecialchars($kp['etternavn'] ?? ''); ?>"
              required
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="stilling">Stilling</label>
            <input
              type="text"
              id="stilling"
              name="stilling"
              placeholder="Eks. Daglig leder"
              value="<?php echo htmlspecialchars($kp['stilling'] ?? ''); ?>"
            />
          </div>
          <div class="form-group">
            <label for="avdeling">Avdeling</label>
            <input
              type="text"
              id="avdeling"
              name="avdeling"
              placeholder="Eks. Ledelse"
              value="<?php echo htmlspecialchars($kp['avdeling'] ?? ''); ?>"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="telefon">Telefon</label>
            <input
              type="text"
              id="telefon"
              name="telefon"
              value="<?php echo htmlspecialchars($kp['telefon'] ?? ''); ?>"
            />
          </div>
          <div class="form-group">
            <label for="mobil">Mobil</label>
            <input
              type="text"
              id="mobil"
              name="mobil"
              value="<?php echo htmlspecialchars($kp['mobil'] ?? ''); ?>"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="epost">E-post</label>
          <input
            type="email"
            id="epost"
            name="epost"
            value="<?php echo htmlspecialchars($kp['epost'] ?? ''); ?>"
          />
        </div>

        <div class="form-actions">
          <a href="kunde_detalj.php?id=<?php echo $kunde_id; ?>" class="btn secondary">Avbryt</a>
          <button type="submit" class="btn primary">
            <?php echo $redigerer ? 'Lagre endringer' : 'Legg til'; ?>
          </button>
        </div>

      </form>
    </div>

  </main>
</div>

</body>
</html>

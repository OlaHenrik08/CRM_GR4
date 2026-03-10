<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kunde – CRM</title>
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
        <!--
          Celine setter $redigerer = isset($_GET['id'])
          slik at tittelen blir riktig avhengig av om vi oppretter eller redigerer
        -->
        <h1><?php echo $redigerer ? 'Rediger kunde' : 'Ny kunde'; ?></h1>
        <p class="subtitle">
          <?php echo $redigerer ? 'Oppdater informasjon om kunden' : 'Fyll inn informasjon om ny kunde'; ?>
        </p>
      </div>
      <a href="index.php" class="btn secondary">← Tilbake</a>
    </div>

    <!-- Feilmelding vises her hvis Celine sender en fra PHP -->
    <?php if (!empty($feil)) { ?>
      <div class="alert error"><?php echo htmlspecialchars($feil); ?></div>
    <?php } ?>

    <!--
      Skjemaet sender data til samme side (kunde_skjema.php).
      Celine tar imot med $_POST og lagrer i databasen.
    -->
    <div class="form-card">
      <form method="POST" action="kunde_skjema.php<?php echo $redigerer ? '?id=' . $kunde['kunde_id'] : ''; ?>">

        <div class="form-row">
          <div class="form-group">
            <label for="firmanavn">Firmanavn <span class="required">*</span></label>
            <input
              type="text"
              id="firmanavn"
              name="firmanavn"
              placeholder="Eks. Grenland Data AS"
              value="<?php echo htmlspecialchars($kunde['firmanavn'] ?? ''); ?>"
              required
            />
          </div>
          <div class="form-group">
            <label for="org_nr">Organisasjonsnummer <span class="required">*</span></label>
            <input
              type="text"
              id="org_nr"
              name="org_nr"
              placeholder="9 siffer"
              maxlength="9"
              value="<?php echo htmlspecialchars($kunde['org_nr'] ?? ''); ?>"
              required
            />
          </div>
        </div>

        <div class="form-group">
          <label for="adresse">Adresse</label>
          <input
            type="text"
            id="adresse"
            name="adresse"
            placeholder="Eks. Storgata 10"
            value="<?php echo htmlspecialchars($kunde['adresse'] ?? ''); ?>"
          />
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="postnr">Postnummer</label>
            <input
              type="text"
              id="postnr"
              name="postnr"
              placeholder="4 siffer"
              maxlength="4"
              value="<?php echo htmlspecialchars($kunde['postnr'] ?? ''); ?>"
            />
          </div>
          <div class="form-group">
            <label for="poststed">Poststed</label>
            <input
              type="text"
              id="poststed"
              name="poststed"
              placeholder="Eks. Skien"
              value="<?php echo htmlspecialchars($kunde['poststed'] ?? ''); ?>"
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
              placeholder="Eks. 900 11 222"
              value="<?php echo htmlspecialchars($kunde['telefon'] ?? ''); ?>"
            />
          </div>
          <div class="form-group">
            <label for="epost">E-post</label>
            <input
              type="email"
              id="epost"
              name="epost"
              placeholder="Eks. post@firma.no"
              value="<?php echo htmlspecialchars($kunde['epost'] ?? ''); ?>"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status">
            <!--
              selected-attributtet vises på riktig valg når vi redigerer.
              Celine fyller inn $kunde['status'] fra databasen.
            -->
            <option value="Aktiv"   <?php echo ($kunde['status'] ?? '') === 'Aktiv'   ? 'selected' : ''; ?>>Aktiv</option>
            <option value="Inaktiv" <?php echo ($kunde['status'] ?? '') === 'Inaktiv' ? 'selected' : ''; ?>>Inaktiv</option>
          </select>
        </div>

        <div class="form-actions">
          <a href="index.php" class="btn secondary">Avbryt</a>
          <button type="submit" class="btn primary">
            <?php echo $redigerer ? 'Lagre endringer' : 'Opprett kunde'; ?>
          </button>
        </div>

      </form>
    </div>

  </main>
</div>

</body>
</html>

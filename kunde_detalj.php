<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kundedetaljer – CRM</title>
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
        <!-- Viser firmanavnet til kunden vi ser på -->
        <h1><?php echo htmlspecialchars($kunde['firmanavn']); ?></h1>
        <p class="subtitle">Kundedetaljer</p>
      </div>
      <div class="btn-group">
        <a href="kunde_skjema.php?id=<?php echo $kunde['kunde_id']; ?>" class="btn edit-btn">Rediger</a>
        <a href="index.php" class="btn secondary">← Tilbake</a>
      </div>
    </div>

    <!-- Informasjonskort for kunden -->
    <div class="detail-grid">
      <div class="detail-card">
        <div class="detail-card-title">Firmainfo</div>

        <div class="detail-row">
          <span class="label">Org.nr</span>
          <span><?php echo htmlspecialchars($kunde['org_nr']); ?></span>
        </div>
        <div class="detail-row">
          <span class="label">Adresse</span>
          <span><?php echo htmlspecialchars($kunde['adresse']); ?></span>
        </div>
        <div class="detail-row">
          <span class="label">Postnr / Poststed</span>
          <span>
            <?php echo htmlspecialchars($kunde['postnr']); ?>
            <?php echo htmlspecialchars($kunde['poststed']); ?>
          </span>
        </div>
        <div class="detail-row">
          <span class="label">Telefon</span>
          <span><?php echo htmlspecialchars($kunde['telefon']); ?></span>
        </div>
        <div class="detail-row">
          <span class="label">E-post</span>
          <span><?php echo htmlspecialchars($kunde['epost']); ?></span>
        </div>
        <div class="detail-row">
          <span class="label">Status</span>
          <?php if ($kunde['status'] === 'Aktiv') { ?>
            <span class="badge badge-aktiv">Aktiv</span>
          <?php } else { ?>
            <span class="badge badge-inaktiv">Inaktiv</span>
          <?php } ?>
        </div>
        <div class="detail-row">
          <span class="label">Opprettet</span>
          <span><?php echo date('d.m.Y', strtotime($kunde['opprettet_dato'])); ?></span>
        </div>
      </div>
    </div>

    <!-- Kontaktpersoner knyttet til denne kunden -->
    <div class="topbar" style="margin-top: 40px;">
      <h2>Kontaktpersoner</h2>
      <a href="kontakt_skjema.php?kunde_id=<?php echo $kunde['kunde_id']; ?>" class="btn primary">
        + Ny kontaktperson
      </a>
    </div>

    <section class="table-section">
      <table>
        <thead>
          <tr>
            <th>Navn</th>
            <th>Stilling</th>
            <th>Avdeling</th>
            <th>Telefon / Mobil</th>
            <th>E-post</th>
            <th>Handlinger</th>
          </tr>
        </thead>
        <tbody>

          <?php
          // Celine henter kontaktpersoner fra databasen med WHERE kunde_id = $id
          // og lager $kpResult. Løkken under viser én rad per kontaktperson.
          while ($kp = mysqli_fetch_assoc($kpResult)) {
          ?>
          <tr>
            <td data-label="Navn">
              <?php echo htmlspecialchars($kp['fornavn'] . ' ' . $kp['etternavn']); ?>
            </td>
            <td data-label="Stilling"><?php echo htmlspecialchars($kp['stilling']); ?></td>
            <td data-label="Avdeling"><?php echo htmlspecialchars($kp['avdeling']); ?></td>
            <td data-label="Telefon / Mobil">
              <?php echo htmlspecialchars($kp['telefon']); ?><br>
              <span style="color:#888"><?php echo htmlspecialchars($kp['mobil']); ?></span>
            </td>
            <td data-label="E-post"><?php echo htmlspecialchars($kp['epost']); ?></td>
            <td data-label="Handlinger">
              <div class="action-buttons">
                <a href="kontakt_skjema.php?id=<?php echo $kp['kontakt_id']; ?>&kunde_id=<?php echo $kunde['kunde_id']; ?>" class="btn edit-btn">Rediger</a>
                <a href="slett_kontakt.php?id=<?php echo $kp['kontakt_id']; ?>&kunde_id=<?php echo $kunde['kunde_id']; ?>"
                   class="btn delete-btn"
                   onclick="return confirm('Er du sikker?')">Slett</a>
              </div>
            </td>
          </tr>
          <?php } ?>

        </tbody>
      </table>
    </section>

  </main>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kontaktpersoner – CRM</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="app">

  <aside class="sidebar">
    <div class="logo">CRM<span class="logo-dot">.</span></div>
    <nav>
      <ul>
        <li><a href="index.php">Kunder</a></li>
        <li><a href="kontaktpersoner.php" class="active">Kontaktpersoner</a></li>
        <li><a href="logout.php">Logg ut</a></li>
      </ul>
    </nav>
    <div class="sidebar-footer">Gruppe 4</div>
  </aside>

  <main class="main-content">

    <div class="topbar">
      <div>
        <h1>Kontaktpersoner</h1>
        <p class="subtitle">Oversikt over alle kontaktpersoner</p>
      </div>
    </div>

    <!-- Søk sender søkeordet tilbake til samme side -->
    <section class="toolbar">
      <form method="GET" action="kontaktpersoner.php">
        <input
          type="text"
          name="sok"
          placeholder="Søk etter navn, stilling, e-post..."
          value="<?php echo isset($_GET['sok']) ? htmlspecialchars($_GET['sok']) : ''; ?>"
        />
        <button type="submit" class="btn secondary">Søk</button>
      </form>
    </section>

    <section class="table-section">
      <table>
        <thead>
          <tr>
            <th>Navn</th>
            <th>Firma</th>
            <th>Stilling</th>
            <th>Avdeling</th>
            <th>Telefon / Mobil</th>
            <th>E-post</th>
            <th>Handlinger</th>
          </tr>
        </thead>
        <tbody>

          <?php
          // Celine henter alle kontaktpersoner med JOIN mot kunde-tabellen,
          // slik at vi også kan vise firmanavnet.
          // SELECT kp.*, k.firmanavn, k.kunde_id
          // FROM kontaktperson kp
          // JOIN kunde k ON kp.kunde_id = k.kunde_id
          // ORDER BY kp.etternavn ASC
          //
          // Løkken under viser én rad per kontaktperson.
          while ($kp = mysqli_fetch_assoc($result)) {
          ?>
          <tr>
            <td data-label="Navn">
              <?php echo htmlspecialchars($kp['fornavn'] . ' ' . $kp['etternavn']); ?>
            </td>
            <td data-label="Firma">
              <!-- Lenke til kundens detaljside -->
              <a href="kunde_detalj.php?id=<?php echo $kp['kunde_id']; ?>">
                <?php echo htmlspecialchars($kp['firmanavn']); ?>
              </a>
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
                <a href="kontakt_skjema.php?id=<?php echo $kp['kontakt_id']; ?>&kunde_id=<?php echo $kp['kunde_id']; ?>" class="btn edit-btn">Rediger</a>
                <a href="slett_kontakt.php?id=<?php echo $kp['kontakt_id']; ?>&kunde_id=<?php echo $kp['kunde_id']; ?>"
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

<script src="script.js"></script>
</body>
</html>

<?php
session_start();
include 'db.php';

// Sjekker om brukeren er logget inn
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// Henter kunde_id fra URL
$id = $_GET['id'];

// Henter kundeinformasjon fra databasen
$sql = "SELECT * FROM kunde WHERE kunde_id = $id";
$result = $conn->query($sql);
$kunde = mysqli_fetch_assoc($result);

// Hvis kunden ikke finnes, send tilbake til oversikten
if(!$kunde){
    header("Location: index.php");
    exit();
}

// Henter kontaktpersoner tilknyttet denne kunden
$sql2 = "SELECT * FROM kontaktperson WHERE kunde_id = $id ORDER BY etternavn ASC";
$kontakter = $conn->query($sql2);
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($kunde['firmanavn']); ?> - CRM Gruppe 4</title>
  <link rel="stylesheet" href="assets/style.css" />
  <script src="assets/script.js"></script>
</head>
<body>

<div class="app">

  <aside class="sidebar">
    <div class="logo">CRM<span class="logo-dot">.</span></div>
    <nav>
      <ul>
        <li><a href="index.php" class="active">Kunder</a></li>
        <li><a href="kontaktpersoner.php">Kontaktpersoner</a></li>
        <li><a href="logout.php">Logg ut</a></li>
      </ul>
    </nav>
    <div class="sidebar-footer">Gruppe 4</div>
  </aside>

  <main class="main-content">

    <div class="topbar">
      <div>
        <h1><?php echo htmlspecialchars($kunde['firmanavn']); ?></h1>
        <p class="subtitle">Kundedetaljer</p>
      </div>
      <div class="action-buttons">
        <a href="kunde_skjema.php?id=<?php echo $kunde['kunde_id']; ?>" class="btn edit-btn">Rediger</a>
        <a href="index.php" class="btn secondary">Tilbake</a>
      </div>
    </div>

    <!-- Kundeinformasjon -->
    <section class="table-section table-section-mb">
      <table>
        <tbody>
          <tr>
            <th class="label-col">Firmanavn</th>
            <td><?php echo htmlspecialchars($kunde['firmanavn']); ?></td>
          </tr>
          <tr>
            <th>Org.nr</th>
            <td><?php echo htmlspecialchars($kunde['org_nr']); ?></td>
          </tr>
          <tr>
            <th>Poststed</th>
            <td><?php echo htmlspecialchars($kunde['poststed']); ?></td>
          </tr>
          <tr>
            <th>Telefon</th>
            <td><?php echo htmlspecialchars($kunde['telefon']); ?></td>
          </tr>
          <tr>
            <th>E-post</th>
            <td><?php echo htmlspecialchars($kunde['epost']); ?></td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              <?php if ($kunde['status'] === 'Aktiv') { ?>
                <span class="badge badge-aktiv">Aktiv</span>
              <?php } else { ?>
                <span class="badge badge-inaktiv">Inaktiv</span>
              <?php } ?>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- Kontaktpersoner for denne kunden -->
    <div class="topbar">
      <div>
        <h2>Kontaktpersoner</h2>
        <p class="subtitle">Kontaktpersoner tilknyttet denne kunden</p>
      </div>
      <a href="kontakt_skjema.php?kunde_id=<?php echo $kunde['kunde_id']; ?>" class="btn primary">+ Ny kontaktperson</a>
    </div>

    <section class="table-section">
      <table>
        <thead>
          <tr>
            <th>Navn</th>
            <th>Stilling</th>
            <th>Avdeling</th>
            <th>Telefon</th>
            <th>E-post</th>
            <th>Handlinger</th>
          </tr>
        </thead>
        <tbody>

          <?php if ($kontakter && $kontakter->num_rows > 0) { ?>
            <?php while ($k = mysqli_fetch_assoc($kontakter)) { ?>
            <tr>
              <td data-label="Navn"><?php echo htmlspecialchars($k['fornavn'] . ' ' . $k['etternavn']); ?></td>
              <td data-label="Stilling"><?php echo htmlspecialchars($k['stilling']); ?></td>
              <td data-label="Avdeling"><?php echo htmlspecialchars($k['avdeling']); ?></td>
              <td data-label="Telefon"><?php echo htmlspecialchars($k['telefon']); ?></td>
              <td data-label="E-post"><?php echo htmlspecialchars($k['epost']); ?></td>
              <td data-label="Handlinger">
                <div class="action-buttons">
                  <a href="kontakt_skjema.php?id=<?php echo $k['kontakt_id']; ?>" class="btn edit-btn">Rediger</a>
                  <a href="slett_kontakt.php?id=<?php echo $k['kontakt_id']; ?>&kunde_id=<?php echo $kunde['kunde_id']; ?>"
                     class="btn delete-btn"
                     onclick="return confirm('Er du sikker på at du vil slette denne kontaktpersonen?')">Slett</a>
                </div>
              </td>
            </tr>
            <?php } ?>
          <?php } else { ?>
            <tr>
              <td colspan="6" class="tom-rad">Ingen kontaktpersoner registrert for denne kunden.</td>
            </tr>
          <?php } ?>

        </tbody>
      </table>
    </section>

  </main>
</div>

</body>
</html>
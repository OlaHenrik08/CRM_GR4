<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Definer søkevariabel
$sok = trim($_GET['sok'] ?? '');

if ($sok !== '') {
    $stmt = $conn->prepare("SELECT kp.*, k.firmanavn
                            FROM kontaktperson kp
                            LEFT JOIN kunde k ON kp.kunde_id = k.kunde_id
                            WHERE kp.fornavn LIKE ? OR kp.etternavn LIKE ? OR kp.epost LIKE ? OR k.firmanavn LIKE ?
                            ORDER BY kp.etternavn ASC, kp.fornavn ASC");
    $like = "%$sok%";
    $stmt->bind_param('ssss', $like, $like, $like, $like);
    $stmt->execute();
    $kontakter = $stmt->get_result();
    $stmt->close();
} else {
    $kontakter = $conn->query("SELECT kp.*, k.firmanavn
                               FROM kontaktperson kp
                               LEFT JOIN kunde k ON kp.kunde_id = k.kunde_id
                               ORDER BY kp.etternavn ASC, kp.fornavn ASC");
}

// Hent alle kontaktpersoner med firmanavn
$kontakter = $conn->query("SELECT kp.*, k.firmanavn
                           FROM kontaktperson kp
                           LEFT JOIN kunde k ON kp.kunde_id = k.kunde_id
                           ORDER BY kp.etternavn ASC, kp.fornavn ASC");
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kontaktpersoner - CRM Gruppe 4</title>
  <link rel="stylesheet" href="assets/style.css" />
  <script src="assets/script.js"></script>
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
        <p class="subtitle">Oversikt over alle kontaktpersoner i CRM-systemet</p>
      </div>
      <a href="kontakt_skjema.php" class="btn primary">+ Ny kontaktperson</a>
    </div>

        <section class="toolbar">
      <form method="GET" action="kontaktpersoner.php">
        <input
          type="text"
          id="myInput"
          onkeyup="myFunction()"
          name="sok"
          placeholder="Søk etter firmanavn, org.nr, e-post..."
          value="<?php echo h($sok); ?>"
        />
        <button type="submit" class="btn secondary">Søk</button>
        <?php if ($sok !== ''): ?>
          <a href="kontaktpersoner.php" class="btn secondary">Nullstill</a>
        <?php endif; ?>
      </form>
    </section>

    <section class="table-section">
      <table>
        <thead>
          <tr>
            <th>Navn</th>
            <th>Firma</th>
            <th>Stilling</th>
            <th>E-post</th>
            <th>Telefon</th>
            <th>Handlinger</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($kontakter && $kontakter->num_rows > 0): ?>
            <?php while ($row = $kontakter->fetch_assoc()): ?>
            <tr>
              <td><?php echo h($row['fornavn'] . ' ' . $row['etternavn']); ?></td>
              <td><?php echo h($row['firmanavn'] ?? ''); ?></td>
              <td><?php echo h($row['stilling']); ?></td>
              <td><?php echo h($row['epost']); ?></td>
              <td><?php echo h($row['telefon'] ?: $row['mobil']); ?></td>
              <td>
                <div class="action-buttons">
                  <a href="kontakt_skjema.php?id=<?php echo (int)$row['kontakt_id']; ?>" class="btn edit-btn">Rediger</a>
                  <a href="slett_kontakt.php?id=<?php echo (int)$row['kontakt_id']; ?>&kunde_id=<?php echo (int)$row['kunde_id']; ?>"
                    class="btn delete-btn"
                    onclick="return confirm('Er du sikker på at du vil slette denne kontaktpersonen?')">Slett</a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="tom-rad">Ingen kontaktpersoner registrert ennå.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

  </main>
</div>

</body>
</html>
<?php $conn->close(); ?>
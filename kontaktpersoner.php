<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Slett kontaktperson
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delid = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM kontaktperson WHERE kontakt_id = ?");
    $stmt->bind_param('i', $delid);
    $stmt->execute();
    $stmt->close();
    header("Location: kontaktpersoner.php");
    exit();
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
                  <a href="kontaktpersoner.php?delete=<?php echo (int)$row['kontakt_id']; ?>"
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
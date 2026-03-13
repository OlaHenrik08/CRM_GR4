<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sok = trim($_GET['sok'] ?? '');

if ($sok !== '') {
    $stmt = $conn->prepare("SELECT * FROM kunde WHERE firmanavn LIKE ? OR org_nr LIKE ? OR epost LIKE ?");
    $like = "%$sok%";
    $stmt->bind_param('sss', $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM kunde ORDER BY firmanavn ASC");
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CRM - Gruppe 4</title>
  <link rel="stylesheet" href="style.css" />
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
        <h1>Kundeoversikt</h1>
        <p class="subtitle">Administrer kunder i CRM-systemet</p>
      </div>
      <a href="kunde_skjema.php" class="btn primary">+ Ny kunde</a>
    </div>

    <section class="toolbar">
      <form method="GET" action="index.php">
        <input
          type="text"
          name="sok"
          placeholder="Søk etter firmanavn, org.nr, e-post..."
          value="<?php echo h($sok); ?>"
        />
        <button type="submit" class="btn secondary">Søk</button>
        <?php if ($sok !== ''): ?>
          <a href="index.php" class="btn secondary">Nullstill</a>
        <?php endif; ?>
      </form>
    </section>

    <section class="table-section">
      <table>
        <thead>
          <tr>
            <th>Firmanavn</th>
            <th>Org.nr</th>
            <th>Poststed</th>
            <th>Telefon</th>
            <th>E-post</th>
            <th>Opprettet</th>
            <th>Status</th>
            <th>Handlinger</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($kunde = $result->fetch_assoc()): ?>
            <tr>
              <td data-label="Firmanavn"><?php echo h($kunde['firmanavn']); ?></td>
              <td data-label="Org.nr"><?php echo h($kunde['org_nr']); ?></td>
              <td data-label="Poststed"><?php echo h($kunde['poststed']); ?></td>
              <td data-label="Telefon"><?php echo h($kunde['telefon']); ?></td>
              <td data-label="E-post"><?php echo h($kunde['epost']); ?></td>
              <td data-label="Opprettet"><?php echo $kunde['opprettet_dato'] ? date('d.m.Y', strtotime($kunde['opprettet_dato'])) : '-'; ?></td>
              <td data-label="Status">
                <?php if ($kunde['status'] === 'Aktiv'): ?>
                  <span class="badge badge-aktiv">Aktiv</span>
                <?php else: ?>
                  <span class="badge badge-inaktiv">Inaktiv</span>
                <?php endif; ?>
              </td>
              <td data-label="Handlinger">
                <div class="action-buttons">
                  <a href="kunde_detalj.php?id=<?php echo (int)$kunde['kunde_id']; ?>" class="btn view-btn">Vis</a>
                  <a href="kunde_skjema.php?id=<?php echo (int)$kunde['kunde_id']; ?>" class="btn edit-btn">Rediger</a>
                  <a href="slett_kunde.php?id=<?php echo (int)$kunde['kunde_id']; ?>"
                     class="btn delete-btn"
                     onclick="return confirm('Er du sikker på at du vil slette denne kunden?')">Slett</a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="tom-rad">
                <?php echo $sok !== '' ? 'Ingen kunder funnet for "' . h($sok) . '".' : 'Ingen kunder registrert ennå.'; ?>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

  </main>
</div>

</body>
</html>

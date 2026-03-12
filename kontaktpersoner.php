<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Hent kunder for select-listen
$kunden = $conn->query("SELECT kunde_id, firmanavn FROM kunde ORDER BY firmanavn ASC");

// Forhåndsvalgt kunde fra URL (f.eks. fra kunde_detalj.php)
$preselect_kunde = isset($_GET['kunde_id']) && is_numeric($_GET['kunde_id']) ? (int)$_GET['kunde_id'] : null;

// Opprett eller oppdater
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kontakt_id = isset($_POST['kontakt_id']) && is_numeric($_POST['kontakt_id']) ? (int)$_POST['kontakt_id'] : null;
    $kunde_id   = isset($_POST['kunde_id']) && is_numeric($_POST['kunde_id']) ? (int)$_POST['kunde_id'] : null;
    $fornavn    = trim($_POST['fornavn'] ?? '');
    $etternavn  = trim($_POST['etternavn'] ?? '');
    $stilling   = trim($_POST['stilling'] ?? '');
    $avdeling   = trim($_POST['avdeling'] ?? '');
    $epost      = trim($_POST['epost'] ?? '');
    $telefon    = trim($_POST['telefon'] ?? '');
    $mobil      = trim($_POST['mobil'] ?? '');

    if ($fornavn === '' || $etternavn === '') {
        $error = "Fornavn og etternavn kan ikke være tomt.";
    } elseif ($kontakt_id) {
        $stmt = $conn->prepare("UPDATE kontaktperson SET kunde_id=?, fornavn=?, etternavn=?, stilling=?, avdeling=?, epost=?, telefon=?, mobil=? WHERE kontakt_id=?");
        $stmt->bind_param('isssssssi', $kunde_id, $fornavn, $etternavn, $stilling, $avdeling, $epost, $telefon, $mobil, $kontakt_id);
        $stmt->execute();
        $stmt->close();
        header("Location: kontaktpersoner.php");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO kontaktperson (kunde_id, fornavn, etternavn, stilling, avdeling, epost, telefon, mobil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssssss', $kunde_id, $fornavn, $etternavn, $stilling, $avdeling, $epost, $telefon, $mobil);
        $stmt->execute();
        $stmt->close();
        header("Location: kontaktpersoner.php");
        exit();
    }
}

// Slett
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delid = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM kontaktperson WHERE kontakt_id = ?");
    $stmt->bind_param('i', $delid);
    $stmt->execute();
    $stmt->close();
    header("Location: kontaktpersoner.php");
    exit();
}

// Last for redigering hvis ?edit=ID
$edit = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM kontaktperson WHERE kontakt_id = ?");
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Hent alle kontaktpersoner
$kontakter = $conn->query("SELECT kp.*, k.firmanavn
                           FROM kontaktperson kp
                           LEFT JOIN kunde k ON kp.kunde_id = k.kunde_id
                           ORDER BY kp.etternavn ASC, kp.fornavn ASC");

$tittel = $edit ? 'Rediger kontakt' : 'Ny kontaktperson';
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kontaktpersoner - CRM Gruppe 4</title>
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
        <p class="subtitle">Administrer kontaktpersoner i CRM-systemet</p>
      </div>
    </div>

    <?php if (!empty($error)): ?>
      <p class="error"><?php echo h($error); ?></p>
    <?php endif; ?>

    <section class="table-section table-section-mb">
      <h2><?php echo h($tittel); ?></h2>
      <form method="POST" action="kontaktpersoner.php">
        <?php if ($edit): ?>
          <input type="hidden" name="kontakt_id" value="<?php echo (int)$edit['kontakt_id']; ?>">
        <?php endif; ?>

        <div class="form-row">
          <label for="kunde_id">Kunde</label>
          <select id="kunde_id" name="kunde_id">
            <option value="">-- Velg kunde --</option>
            <?php if ($kunden && $kunden->num_rows): ?>
              <?php while ($k = $kunden->fetch_assoc()): ?>
                <?php
                  $selected_id = $edit['kunde_id'] ?? $preselect_kunde;
                  $selected = ($selected_id == $k['kunde_id']) ? 'selected' : '';
                ?>
                <option value="<?php echo (int)$k['kunde_id']; ?>" <?php echo $selected; ?>>
                  <?php echo h($k['firmanavn']); ?>
                </option>
              <?php endwhile; ?>
            <?php endif; ?>
          </select>
        </div>

        <div class="form-row">
          <label for="fornavn">Fornavn</label>
          <input id="fornavn" name="fornavn" type="text" required value="<?php echo h($edit['fornavn'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="etternavn">Etternavn</label>
          <input id="etternavn" name="etternavn" type="text" required value="<?php echo h($edit['etternavn'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="stilling">Stilling</label>
          <input id="stilling" name="stilling" type="text" value="<?php echo h($edit['stilling'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="avdeling">Avdeling</label>
          <input id="avdeling" name="avdeling" type="text" value="<?php echo h($edit['avdeling'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="epost">E-post</label>
          <input id="epost" name="epost" type="email" value="<?php echo h($edit['epost'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="telefon">Telefon</label>
          <input id="telefon" name="telefon" type="text" value="<?php echo h($edit['telefon'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="mobil">Mobil</label>
          <input id="mobil" name="mobil" type="text" value="<?php echo h($edit['mobil'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <button type="submit" class="btn primary"><?php echo $edit ? 'Oppdater' : 'Legg til'; ?></button>
          <?php if ($edit): ?>
            <a href="kontaktpersoner.php" class="btn secondary btn-margin-left">Avbryt</a>
          <?php endif; ?>
        </div>
      </form>
    </section>

    <div class="topbar">
      <div>
        <h2>Alle kontaktpersoner</h2>
      </div>
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
                  <a href="kontaktpersoner.php?edit=<?php echo (int)$row['kontakt_id']; ?>" class="btn edit-btn">Rediger</a>
                  <a href="kontaktpersoner.php?delete=<?php echo (int)$row['kontakt_id']; ?>"
                     class="btn delete-btn"
                     onclick="return confirm('Slett kontakt?')">Slett</a>
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

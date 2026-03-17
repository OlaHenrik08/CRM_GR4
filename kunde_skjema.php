<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$kunde = null;
$edit_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

// Last eksisterende kunde for redigering
if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM kunde WHERE kunde_id = ?");
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $kunde = $result->fetch_assoc();
    $stmt->close();
}

// Håndterer innsendt skjema
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firmanavn = trim($_POST['firmanavn'] ?? '');
    $org_nr    = trim($_POST['org_nr'] ?? '');
    $poststed  = trim($_POST['poststed'] ?? '');
    $telefon   = trim($_POST['telefon'] ?? '');
    $epost     = trim($_POST['epost'] ?? '');
    $status    = $_POST['status'] === 'Inaktiv' ? 'Inaktiv' : 'Aktiv';
    $post_id   = isset($_POST['kunde_id']) && is_numeric($_POST['kunde_id']) ? (int)$_POST['kunde_id'] : null;

    if ($firmanavn === '' || $org_nr === '') {
        $error = "Firmanavn og org.nr kan ikke være tomt.";
    } elseif ($post_id) {
        $stmt = $conn->prepare("UPDATE kunde SET firmanavn=?, org_nr=?, poststed=?, telefon=?, epost=?, status=? WHERE kunde_id=?");
        $stmt->bind_param('ssssssi', $firmanavn, $org_nr, $poststed, $telefon, $epost, $status, $post_id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO kunde (firmanavn, org_nr, poststed, telefon, epost, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $firmanavn, $org_nr, $poststed, $telefon, $epost, $status);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}

$tittel = $kunde ? 'Rediger kunde' : 'Ny kunde';
$undertittel = $kunde ? 'Oppdater kundeinformasjon' : 'Legg til ny kunde i CRM-systemet';
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo h($tittel); ?> - CRM Gruppe 4</title>
  <link rel="stylesheet" href="assets/style.css" />
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
        <h1><?php echo h($tittel); ?></h1>
        <p class="subtitle"><?php echo h($undertittel); ?></p>
      </div>
      <a href="index.php" class="btn secondary">Tilbake til oversikten</a>
    </div>

    <?php if (isset($error)): ?>
      <p class="error"><?php echo h($error); ?></p>
    <?php endif; ?>

    <section class="form-section">
      <form method="POST" action="">
        <?php if ($kunde): ?>
          <input type="hidden" name="kunde_id" value="<?php echo (int)$kunde['kunde_id']; ?>">
        <?php endif; ?>

        <div class="form-row">
          <label for="firmanavn">Firmanavn</label>
          <input type="text" id="firmanavn" name="firmanavn" required
                 value="<?php echo h($kunde['firmanavn'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="org_nr">Org.nr</label>
          <input type="text" id="org_nr" name="org_nr" required
                 value="<?php echo h($kunde['org_nr'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="poststed">Poststed</label>
          <input type="text" id="poststed" name="poststed"
                 value="<?php echo h($kunde['poststed'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="telefon">Telefon</label>
          <input type="text" id="telefon" name="telefon"
                 value="<?php echo h($kunde['telefon'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="epost">E-post</label>
          <input type="email" id="epost" name="epost"
                 value="<?php echo h($kunde['epost'] ?? ''); ?>">
        </div>

        <div class="form-row">
          <label for="status">Status</label>
          <select id="status" name="status">
            <option value="Aktiv"   <?php echo (!$kunde || $kunde['status'] === 'Aktiv')   ? 'selected' : ''; ?>>Aktiv</option>
            <option value="Inaktiv" <?php echo ($kunde && $kunde['status'] === 'Inaktiv') ? 'selected' : ''; ?>>Inaktiv</option>
          </select>
        </div>

        <div class="form-row">
          <input type="submit" value="<?php echo $kunde ? 'Oppdater kunde' : 'Legg til kunde'; ?>" class="btn primary">
        </div>
      </form>
    </section>

  </main>
</div>

</body>
</html>

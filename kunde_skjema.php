<?php
session_start();
include 'db.php';

// Håndterer innsendt skjema
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firmanavn = $_POST['firmanavn'];
    $org_nr = $_POST['org_nr'];
    $poststed = $_POST['poststed'];
    $telefon = $_POST['telefon'];
    $epost = $_POST['epost'];
    $status = $_POST['status'];

    $sql = "INSERT INTO kunde (firmanavn, org_nr, poststed, telefon, epost, status)
            VALUES ('$firmanavn', '$org_nr', '$poststed', '$telefon', '$epost', '$status')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Databasefeil: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ny kunde - CRM Gruppe 4</title>
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
        <h1>Ny kunde</h1>
        <p class="subtitle">Legg til ny kunde i CRM-systemet</p>
      </div>
      <a href="index.php" class="btn secondary">Tilbake til oversikten</a>
    </div>

    <?php if(isset($error)) { ?>
      <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <section class="form-section">
      <form method="POST" action="">
        <label for="firmanavn">Firmanavn:</label>
        <input type="text" id="firmanavn" name="firmanavn" required>

        <label for="org_nr">Org.nr:</label>
        <input type="text" id="org_nr" name="org_nr" required>

        <label for="poststed">Poststed:</label>
        <input type="text" id="poststed" name="poststed" required>

        <label for="telefon">Telefon:</label>
        <input type="text" id="telefon" name="telefon" required>

        <label for="epost">E-post:</label>
        <input type="email" id="epost" name="epost" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
          <option value="Aktiv">Aktiv</option>
          <option value="Inaktiv">Inaktiv</option>
        </select>

        <input type="submit" value="Legg til kunde" class="btn primary">
      </form>
    </section>

  </main>
</div>

</body>
</html>
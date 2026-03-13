<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Henter kontakt_id hvis vi redigerer
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Henter kunde_id hvis vi kommer fra kunde_detalj
$kunde_id_fra_url = isset($_GET['kunde_id']) ? $_GET['kunde_id'] : null;

// Last inn eksisterende kontakt ved redigering
$kontakt = null;
if ($id) {
    $sql = "SELECT * FROM kontaktperson WHERE kontakt_id = $id";
    $result = $conn->query($sql);
    $kontakt = mysqli_fetch_assoc($result);
}

// Håndterer innsendt skjema
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fornavn   = $_POST['fornavn'];
    $etternavn = $_POST['etternavn'];
    $stilling  = $_POST['stilling'];
    $avdeling  = $_POST['avdeling'];
    $epost     = $_POST['epost'];
    $telefon   = $_POST['telefon'];
    $mobil     = $_POST['mobil'];
    $kunde_id  = $_POST['kunde_id'];
    $tilbake   = $_POST['tilbake']; // husker hvor vi kom fra

    if ($_POST['kontakt_id']) {
        // Oppdaterer eksisterende kontaktperson
        $sql = "UPDATE kontaktperson SET
                    fornavn = '$fornavn',
                    etternavn = '$etternavn',
                    stilling = '$stilling',
                    avdeling = '$avdeling',
                    epost = '$epost',
                    telefon = '$telefon',
                    mobil = '$mobil',
                    kunde_id = '$kunde_id'
                WHERE kontakt_id = " . $_POST['kontakt_id'];

        if ($conn->query($sql) === TRUE) {
            header("Location: $tilbake");
            exit();
        } else {
            $error = "Databasefeil: " . $conn->error;
        }
    } else {
        // Legger til ny kontaktperson
        $sql = "INSERT INTO kontaktperson (fornavn, etternavn, stilling, avdeling, epost, telefon, mobil, kunde_id)
                VALUES ('$fornavn', '$etternavn', '$stilling', '$avdeling', '$epost', '$telefon', '$mobil', '$kunde_id')";

        if ($conn->query($sql) === TRUE) {
            header("Location: $tilbake");
            exit();
        } else {
            $error = "Databasefeil: " . $conn->error;
        }
    }
}

// Bestem hvor "Tilbake" skal peke
if ($kontakt) {
    $tilbake_url = "kunde_detalj.php?id=" . $kontakt['kunde_id'];
} elseif ($kunde_id_fra_url) {
    $tilbake_url = "kunde_detalj.php?id=$kunde_id_fra_url";
} else {
    $tilbake_url = "kontaktpersoner.php";
}

// Henter alle kunder til nedtrekkslisten
$kunder = $conn->query("SELECT kunde_id, firmanavn FROM kunde ORDER BY firmanavn ASC");

$tittel = $kontakt ? 'Rediger kontaktperson' : 'Ny kontaktperson';
$undertittel = $kontakt ? 'Oppdater informasjon om kontaktpersonen' : 'Legg til ny kontaktperson';
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $tittel; ?> - CRM Gruppe 4</title>
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
        <h1><?php echo $tittel; ?></h1>
        <p class="subtitle"><?php echo $undertittel; ?></p>
      </div>
      <a href="<?php echo $tilbake_url; ?>" class="btn secondary">Tilbake</a>
    </div>

    <?php if (isset($error)) { ?>
      <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <section class="form-section">
      <form method="POST" action="">
        <!-- Skjult felt for kontakt_id ved redigering -->
        <input type="hidden" name="kontakt_id" value="<?php echo $kontakt ? $kontakt['kontakt_id'] : ''; ?>">
        <!-- Husker hvor brukeren kom fra slik at vi kan sende dem tilbake riktig sted -->
        <input type="hidden" name="tilbake" value="<?php echo $tilbake_url; ?>">

        <div class="form-row">
          <label for="kunde_id">Tilknyttet kunde</label>
          <select id="kunde_id" name="kunde_id" required>
            <option value="">-- Velg kunde --</option>
            <?php while ($k = mysqli_fetch_assoc($kunder)) { ?>
              <?php
                $selected = '';
                if ($kontakt && $kontakt['kunde_id'] == $k['kunde_id']) $selected = 'selected';
                if (!$kontakt && $kunde_id_fra_url == $k['kunde_id']) $selected = 'selected';
              ?>
              <option value="<?php echo $k['kunde_id']; ?>" <?php echo $selected; ?>>
                <?php echo htmlspecialchars($k['firmanavn']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="form-row">
          <label for="fornavn">Fornavn</label>
          <input type="text" id="fornavn" name="fornavn" required
                 value="<?php echo $kontakt ? htmlspecialchars($kontakt['fornavn']) : ''; ?>">
        </div>

        <div class="form-row">
          <label for="etternavn">Etternavn</label>
          <input type="text" id="etternavn" name="etternavn" required
                 value="<?php echo $kontakt ? htmlspecialchars($kontakt['etternavn']) : ''; ?>">
        </div>

        <div class="form-row">
          <label for="stilling">Stilling</label>
          <input type="text" id="stilling" name="stilling"
                 value="<?php echo $kontakt ? htmlspecialchars($kontakt['stilling']) : ''; ?>">
        </div>

        <div class="form-row">
          <label for="avdeling">Avdeling</label>
          <input type="text" id="avdeling" name="avdeling"
                 value="<?php echo $kontakt ? htmlspecialchars($kontakt['avdeling']) : ''; ?>">
        </div>

        <div class="form-row">
          <label for="telefon">Telefon</label>
          <input type="text" id="telefon" name="telefon"
                 value="<?php echo $kontakt ? htmlspecialchars($kontakt['telefon']) : ''; ?>">
        </div>

        <div class="form-row">
          <label for="mobil">Mobil</label>
          <input type="text" id="mobil" name="mobil"
                 value="<?php echo $kontakt ? htmlspecialchars($kontakt['mobil']) : ''; ?>">
        </div>

        <div class="form-row">
          <label for="epost">E-post</label>
          <input type="email" id="epost" name="epost"
                 value="<?php echo $kontakt ? htmlspecialchars($kontakt['epost']) : ''; ?>">
        </div>

        <div class="form-row">
          <input type="submit" value="<?php echo $kontakt ? 'Oppdater kontaktperson' : 'Legg til kontaktperson'; ?>" class="btn primary">
        </div>
      </form>
    </section>

  </main>
</div>

</body>
</html>
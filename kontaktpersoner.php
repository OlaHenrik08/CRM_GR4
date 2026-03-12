<?php
include 'db.php';

function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

// Hent kunder for select-listen
$kunden = $conn->query("SELECT kunde_id, firmanavn FROM kunde ORDER BY firmanavn");

// Opprett eller oppdater
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kontakt_id = isset($_POST['kontakt_id']) && is_numeric($_POST['kontakt_id']) ? (int)$_POST['kontakt_id'] : null;
    $kunde_id = isset($_POST['kunde_id']) && is_numeric($_POST['kunde_id']) ? (int)$_POST['kunde_id'] : null;
    $fornavn = trim($_POST['fornavn'] ?? '');
    $etternavn = trim($_POST['etternavn'] ?? '');
    $stilling = trim($_POST['stilling'] ?? '');
    $avdeling = trim($_POST['avdeling'] ?? '');
    $epost = trim($_POST['epost'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $mobil = trim($_POST['mobil'] ?? '');

    if ($fornavn === '' || $etternavn === '') {
        $error = "Fornavn og etternavn kan ikke være tomt.";
    } else {
        if ($kontakt_id) {
            $stmt = $conn->prepare("UPDATE kontaktperson SET kunde_id = ?, fornavn = ?, etternavn = ?, stilling = ?, avdeling = ?, epost = ?, telefon = ?, mobil = ? WHERE kontakt_id = ?");
            $stmt->bind_param('isssssssi', $kunde_id, $fornavn, $etternavn, $stilling, $avdeling, $epost, $telefon, $mobil, $kontakt_id);
            $stmt->execute();
            $stmt->close();
            header("Location: kontaktpersoner.php");
            exit;
        } else {
            $stmt = $conn->prepare("INSERT INTO kontaktperson (kunde_id, fornavn, etternavn, stilling, avdeling, epost, telefon, mobil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('isssssss', $kunde_id, $fornavn, $etternavn, $stilling, $avdeling, $epost, $telefon, $mobil);
            $stmt->execute();
            $stmt->close();
            header("Location: kontaktpersoner.php");
            exit;
        }
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
    exit;
}

// Last for redigering hvis ?edit=ID
$edit = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT kontakt_id, kunde_id, fornavn, etternavn, stilling, avdeling, epost, telefon, mobil FROM kontaktperson WHERE kontakt_id = ?");
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $res = $stmt->get_result();
    $edit = $res->fetch_assoc();
    $stmt->close();
}

// Hent alle kontaktpersoner
$result = $conn->query("SELECT kp.kontakt_id, kp.kunde_id, kp.fornavn, kp.etternavn, kp.stilling, kp.avdeling, kp.epost, kp.telefon, kp.mobil, k.firmanavn
                        FROM kontaktperson kp
                        LEFT JOIN kunde k ON kp.kunde_id = k.kunde_id
                        ORDER BY kp.etternavn ASC, kp.fornavn ASC");
?>
<!doctype html>
<html lang="no">
<head>
<meta charset="utf-8">
<title>Kontaktpersoner</title>
</head>
<body>

<h1>Kontaktpersoner</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= h($error) ?></p>
<?php endif; ?>

<h2><?= $edit ? 'Rediger kontakt' : 'Ny kontakt' ?></h2>
<form method="post" action="kontaktpersoner.php">
    <div class="form-row">
        <label for="kunde_id">Kunde</label>
        <select id="kunde_id" name="kunde_id">
            <option value="">-- Velg kunde --</option>
            <?php if ($kunden && $kunden->num_rows): ?>
                <?php while ($k = $kunden->fetch_assoc()): ?>
                    <option value="<?= (int)$k['kunde_id'] ?>" <?= (isset($edit['kunde_id']) && $edit['kunde_id'] == $k['kunde_id']) ? 'selected' : '' ?>>
                        <?= h($k['firmanavn']) ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-row">
        <label for="fornavn">Fornavn</label>
        <input id="fornavn" name="fornavn" type="text" required value="<?= h($edit['fornavn'] ?? '') ?>">
    </div>

    <div class="form-row">
        <label for="etternavn">Etternavn</label>
        <input id="etternavn" name="etternavn" type="text" required value="<?= h($edit['etternavn'] ?? '') ?>">
    </div>

    <div class="form-row">
        <label for="stilling">Stilling</label>
        <input id="stilling" name="stilling" type="text" value="<?= h($edit['stilling'] ?? '') ?>">
    </div>

    <div class="form-row">
        <label for="avdeling">Avdeling</label>
        <input id="avdeling" name="avdeling" type="text" value="<?= h($edit['avdeling'] ?? '') ?>">
    </div>

    <div class="form-row">
        <label for="epost">E-post</label>
        <input id="epost" name="epost" type="email" value="<?= h($edit['epost'] ?? '') ?>">
    </div>

    <div class="form-row">
        <label for="telefon">Telefon</label>
        <input id="telefon" name="telefon" type="text" value="<?= h($edit['telefon'] ?? '') ?>">
    </div>

    <div class="form-row">
        <label for="mobil">Mobil</label>
        <input id="mobil" name="mobil" type="text" value="<?= h($edit['mobil'] ?? '') ?>">
    </div>

    <?php if ($edit): ?>
        <input type="hidden" name="kontakt_id" value="<?= (int)$edit['kontakt_id'] ?>">
    <?php endif; ?>

    <div class="form-row">
        <button type="submit"><?= $edit ? 'Oppdater' : 'Legg til' ?></button>
        <?php if ($edit): ?>
            <a href="kontaktpersoner.php">Avbryt</a>
        <?php endif; ?>
    </div>
</form>

<h2>Liste</h2>

<?php if ($result && $result->num_rows): ?>
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
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= h($row['fornavn'] . ' ' . $row['etternavn']) ?></td>
            <td><?= h($row['firmanavn'] ?? '') ?></td>
            <td><?= h($row['stilling']) ?></td>
            <td><?= h($row['epost']) ?></td>
            <td><?= h($row['telefon'] ?: $row['mobil']) ?></td>
            <td class="actions">
                <a href="kontaktpersoner.php?edit=<?= (int)$row['kontakt_id'] ?>">Rediger</a>
                <a href="kontaktpersoner.php?delete=<?= (int)$row['kontakt_id'] ?>" onclick="return confirm('Slett kontakt?')">Slett</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p>Ingen kontakter funnet.</p>
<?php endif; ?>

</body>
</html>
<?php
$conn->close();
?>
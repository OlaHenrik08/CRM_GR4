<?php
session_start();
include "db.php";

$feil = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user["password"])){

            $_SESSION["user"] = $user["username"];
            header("Location: index.php");
            exit();

        } else {
            $feil = "Feil passord";
        }

    } else {
        $feil = "Bruker finnes ikke";
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logg inn - CRM Gruppe 4</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-content">

  <div class="topbar">
    <div>
      <h1>Kundedatabase</h1>
      <p class="subtitle">Logg inn i CRM-systemet</p>
    </div>
  </div>

  <section class="table-section auth-section">

    <?php if($feil){ ?>
      <p class="error"><?php echo $feil; ?></p>
    <?php } ?>

    <form method="POST" action="login.php">

      <div class="form-row">
        <label for="username">Brukernavn</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-row">
        <label for="password">Passord</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-row">
        <button type="submit" class="btn primary">Logg inn</button>
      </div>

      <p class="auth-link">
        Har du ikke bruker? <a href="registrer.php">Registrer deg</a>
      </p>

    </form>

  </section>

</div>

</body>
</html>
<?php

    session_start();

    if ( !isset( $_SESSION["role"] ) || $_SESSION["role"] != "manager" ) {
        header( "Location: index.php" );
    }

    $sessionTimeout = 1800; // 30 minutes

    if ( isset( $_SESSION['last_activity'] ) && ( time() - $_SESSION['last_activity'] ) > $sessionTimeout ) {

        session_unset();
        session_destroy();
        header( "Location: index.php" );
        die();
    } else {
        $_SESSION['last_activity'] = time();
    }

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assignment 5</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2" style="margin: 50px auto">
        <h4 class="alert alert-info">Welcome, Manager</h4>
        <p>Your Email : <strong><?php echo $_SESSION["email"]; ?></strong></p>
        <a href="logout.php" class="btn btn-primary">
          Logout<i class="bi bi-box-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php

    session_start();

    if ( !isset( $_SESSION["role"] ) || $_SESSION["role"] !== "admin" ) {
        header( "Location: index.php" );
    }

    $role     = "admin";
    $email    = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $username = $_POST["username"] ?? "";

    $errorMessage = "";

    if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {

        if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
            $errorMessage = "All fields are required.";

        } elseif ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            $errorMessage = "Invalid email format.";

        } elseif ( strlen( $password ) < 8 ) {
            $errorMessage = "Password must be at least 8 characters long.";

        } elseif ( !preg_match( '/[A-Z]/', $password ) || !preg_match( '/[a-z]/', $password ) || !preg_match( '/\d/', $password ) || !preg_match( '/[^A-Za-z0-9]/', $password ) ) {
            $errorMessage = "Password should include at least one uppercase letter, one lowercase letter, one digit, and one special character.";

        } else {
            $authFile = "./auth.txt";
            $fileData = file_get_contents( $authFile );

            $lines = explode( "\n", $fileData );

            foreach ( $lines as $line ) {
                $userData = explode( ", ", $line );
                if ( isset( $userData[1] ) && $userData[1] === $email ) {
                    $errorMessage = "Email address already in use.";
                    break;
                }
            }

            if ( empty( $errorMessage ) ) {
                $fp = fopen( $authFile, "a" );
                fwrite( $fp, "\n{$role}, {$email}, {$password}, {$username}" );
                fclose( $fp );
                header( "Location: role_management.php" );
            }
        }
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
      <div class="col-lg-8 offset-lg-2" style="margin: 120px auto">

        <p class="text-danger mb-3 text-center">
          <?php
              if ( $errorMessage ) {
                  echo $errorMessage;
              }
          ?>
        </p>

        <div class="card shadow px-3">
          <div class="card-body">
            <h4 class="text-center mb-3">
              <?php
                  echo 'Add a New User';
              ?>
            </h4>
            <form action="" method="POST">
              <div class="mb-3">
                <label for="username_box" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username_box" placeholder="johndoe">
              </div>

              <div class="mb-3">
                <label for="email_box" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email_box" placeholder="name@example.com">

                <div class="mb-3">
                  <label for="password_box" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="password_box" placeholder="******">
                </div>
                <div class="d-grid col-4 mx-auto">
                  <button type="submit" class="btn btn-primary mt-3">
                    <?php
                        echo 'Add User';
                    ?>
                  </button>
                </div>
            </form>

          </div>
        </div>
      </div>
      <a href="role_management.php" class="btn btn-outline-info mt-3">
        Go Back <i class="bi bi-house-gear-fill"></i>
      </a>
    </div>
  </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
  </script>
</body>

</html>
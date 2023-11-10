<?php

    session_start();

    if ( isset( $_SESSION["email"] ) ) {
        header( "Location: index.php" );
    }

    $email    = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $errorMessage = "";

    $fp = fopen( "./auth.txt", "r" );

    $roles     = [];
    $emails    = [];
    $passwords = [];

    if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

        $fp = fopen( "./auth.txt", "r" );

        $roles     = [];
        $emails    = [];
        $passwords = [];

        while ( $data = fgets( $fp ) ) {

            $values = explode( ",", $data );

            array_push( $roles, trim( $values[0] ) );
            array_push( $emails, trim( $values[1] ) );
            array_push( $passwords, trim( $values[2] ) );
        }

        fclose( $fp );

        $validation = false;

        if ( empty( $email ) || empty( $password ) ) {

            $errorMessage = "Both email and password are required.";
        } else {

            for ( $i = 0; $i < count( $roles ); $i++ ) {

                if ( $email == $emails[$i] && $password == $passwords[$i] ) {
                    $_SESSION["role"]  = $roles[$i];
                    $_SESSION["email"] = $emails[$i];
                    header( "Location: index.php" );
                    $validLogin = true;
                    break;
                }

            }
        }

        if ( !$validation ) {
            $errorMessage = "Invalid Email or Password";
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
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2" style="margin: 75px auto">

        <p class="text-danger mb-3 text-center">
          <?php if ( !empty( $errorMessage ) ) {
                  echo $errorMessage;
          }?>
        </p>

        <div class="card shadow px-3 mb-4">
          <div class="card-body">
            <h4 class="text-center mb-4">Please Login to Proceeds</h4>

            <form action="" method="POST">
              <div class="row g-3 align-items-center px-2">
                <div class="col-3">
                  <label for="email_box" class="col-form-label">Email address</label>
                </div>
                <div class="col-9">
                  <input type="email" id="email_box" class="form-control" name="email" placeholder="name@example.com">
                </div>
                <div class="col-3">
                  <label for="password_box" class="col-form-label">Password</label>
                </div>
                <div class="col-9">
                  <input type="password" id="password_box" class="form-control" name="password" placeholder="******">
                </div>
                <div class="col-auto mx-auto">
                  <button type="submit" class="btn btn-info ">Log In</button>
                </div>
              </div>
            </form>

            <div style="display: flex; justify-content: center; align-items: center;margin: 20px 0 10px;">
              <span class="me-2">Not a User?</span>
              <a href="sine_up.php" class="btn btn-warning">Register Now</a>
            </div>

          </div>
        </div>
        <h6 class="alert alert-info text-center">admin : admin1@gmail.com | password : a12345</h6>
        <h6 class="alert alert-info text-center">manager : manager1@gmail.com | password : m12345</h6>
        <h6 class="alert alert-info text-center">user : user1@gmail.com | password : u12345</h6>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
  </script>
</body>

</html>
<?php

    session_start();

    if ( !isset( $_SESSION["role"] ) || $_SESSION["role"] !== "admin" ) {
        header( "Location: index.php" );
    }

    if ( isset( $_GET["email"] ) ) {
        $emailToDelete = $_GET["email"];

        if ( isset( $_POST["confirmDelete"] ) ) {

            $authFile = "./auth.txt";
            $userData = file( $authFile, FILE_IGNORE_NEW_LINES );

            foreach ( $userData as $key => $line ) {
                $userDetails = explode( ", ", $line );
                $userEmail   = trim( $userDetails[1] );

                if ( $userEmail === $emailToDelete ) {

                    unset( $userData[$key] );
                    file_put_contents( $authFile, implode( PHP_EOL, $userData ) );
                    header( "Location: role_management.php" );
                    die();
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-3" style="margin: 50px auto">
        <h4 class="alert alert-danger">Confirm User Deletion</h4>
        <p class="fst-italic">Are you sure you want to delete this user?</p>
        <form method="post" action="delete_user.php?email=<?php echo $emailToDelete; ?>">
          <button type="submit" name="confirmDelete" class="btn btn-danger me-2 ">Confirm</button>
          <a href="role.php" class="btn btn-success">Cancel</a>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
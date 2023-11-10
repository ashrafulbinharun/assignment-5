<?php

    session_start();

    if ( !isset( $_SESSION["role"] ) || $_SESSION["role"] !== "admin" ) {
        header( "Location: index.php" );
    }

    $authFile = "./auth.txt";
    $userData = file( $authFile, FILE_IGNORE_NEW_LINES );

    if ( isset( $_GET["email"] ) ) {
        $emailToEdit     = $_GET["email"];
        $currentUserData = [];

        foreach ( $userData as $line ) {
            $userDetails = explode( ", ", $line );
            $userEmail   = trim( $userDetails[1] );

            if ( $userEmail === $emailToEdit ) {
                $currentUserData = $userDetails;
                break;
            }
        }
    }

    if ( isset( $_POST["updateUser"] ) ) {
        $newUsername = $_POST["newUsername"];
        $newRole     = $_POST["newRole"];

        $currentUserData[3] = $newUsername;
        $currentUserData[0] = $newRole;

        foreach ( $userData as $key => $line ) {
            $userDetails = explode( ", ", $line );
            $userEmail   = trim( $userDetails[1] );

            if ( $userEmail === $emailToEdit ) {
                $userData[$key] = implode( ", ", $currentUserData );
                break;
            }
        }

        file_put_contents( $authFile, implode( PHP_EOL, $userData ) );

        header( "Location: role_management.php" );
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-3" style="margin: 50px auto">
        <h4 class="alert alert-info text-center">Edit User</h4>
        <form method="post" action="edit_user.php?email=<?php echo $emailToEdit; ?>">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $currentUserData[1]; ?>"
              disabled>
          </div>
          <div class="mb-3">
            <label for="newUsername" class="form-label">New Username</label>
            <input type="text" class="form-control" id="newUsername" name="newUsername"
              value="<?php echo $currentUserData[3]; ?>">
          </div>
          <div class="mb-3">
            <label for="newRole" class="form-label">New Role</label>
            <select class="form-select" id="newRole" name="newRole">
              <option value="admin"                                    <?php
                                    if ( $currentUserData[0] === 'admin' ) {echo 'selected';}
                                    ?>>Admin
              </option>
              <option value="user"                                   <?php
                                   if ( $currentUserData[0] === 'user' ) {echo 'selected';}
                                   ?>>User
              </option>
              <option value="manager"                                      <?php
                                      if ( $currentUserData[0] === 'manager' ) {echo 'selected';}
                                      ?>>Manager
              </option>
            </select>
          </div>
          <button type="submit" name="updateUser" class="btn btn-success me-3 ">Update User</button>
          <a href="role_management.php" class="btn btn-outline-primary">Role Management</a>
        </form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
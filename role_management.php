<?php

    session_start();

    if ( !isset( $_SESSION["role"] ) || $_SESSION["role"] != "admin" ) {
        header( "Location: index.php" );
    }

    $userData = [];
    $fp       = fopen( "./auth.txt", "r" );

    while ( $data = fgets( $fp ) ) {
        $values     = explode( ",", $data );
        $role       = trim( $values[0] );
        $email      = trim( $values[1] );
        $username   = trim( $values[3] );
        $userData[] = [
            'role'     => $role,
            'email'    => $email,
            'username' => $username,
        ];
    }

    fclose( $fp );

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
        <h4 class="alert alert-info text-center">Role Management</h4>

        <table class="table table-striped mt-4">
          <thead>
            <tr>
              <th>Role</th>
              <th>Email</th>
              <th>Username</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $userData as $user ): ?>
            <tr>
              <td><?php echo $user['role'] ?></td>
              <td><?php echo $user['email'] ?></td>
              <td><?php echo $user['username'] ?></td>
              <td>
                <a href="edit_user.php?email=<?php echo $user['email'] ?>" class="btn btn-primary btn-sm me-2">
                  Edit User<i class="bi bi-pencil-square"></i>
                </a>
                <a href="delete_user.php?email=<?php echo $user['email'] ?>" class="btn btn-danger btn-sm">
                  Delete <i class="bi bi-trash3"></i>
                </a>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <a href="add_user.php" class="btn btn-secondary me-2">Create User <i
            class="bi bi-person-plus-fill ms-1"></i></a>
        <a href="admin.php" class="btn btn-outline-dark">Home</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php

    session_start();

    if ( $_SESSION["role"] !== "admin" ) {
        header( "Location: index.php" );
        exit;
    }

    require_once './functions.php';

    $errorMessage = "";

    if ( isset( $_GET["email"] ) ) {
        $emailToEdit = $_GET["email"];
        $currentUserData = editUser( $emailToEdit );
    }

    if ( isset( $_POST["updateUser"] ) ) {
        $newUsername = filter_input( INPUT_POST, 'new_username', FILTER_SANITIZE_SPECIAL_CHARS );
        $newRole = $_POST["new_role"];

        if ( updateUser( $newRole, $newUsername, $emailToEdit ) ) {
            header( "Location: role_management.php" );
            exit;
        } else {
            $errorMessage = "Failed to update user details";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assignment 5 | Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3" style="margin: 50px auto">
                <h4 class="alert alert-info text-center">Edit User</h4>
                <?php if ( isset( $errorMessage ) ): ?>
                <p class="text-danger my-3 text-center">
                    <?=$errorMessage;?>
                </p>
                <?php endif;?>
                <form method="POST" action="./edit_user.php?email=<?=urlencode( $emailToEdit );?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?=htmlspecialchars( $currentUserData['email'] )?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="new_username" class="form-label">New Username</label>
                        <input type="text" class="form-control" id="new_username" name="new_username"
                            value="<?=htmlspecialchars( $currentUserData['name'] ?? '' )?>">
                    </div>
                    <div class="mb-3">
                        <label for="new_role" class="form-label">New Role</label>
                        <select class="form-select" id="new_role" name="new_role">
                            <?php $roles = ['admin', 'user', 'manager'];?>
<?php foreach ( $roles as $role ): ?>
                            <option value="<?=$role?>" <?=( $currentUserData['role'] === $role ) ? 'selected' : ''?>>
                                <?=ucfirst( $role )?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </div>


                    <button type="submit" name="updateUser" class="btn btn-success me-3 ">Update User</button>
                    <a href="./role_management.php" class="btn btn-outline-primary">Role Management</a>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
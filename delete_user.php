<?php

    session_start();

    if ( $_SESSION["role"] !== "admin" ) {
        header( "Location: index.php" );
        exit;
    }

    require_once './functions.php';

    $errorMessage = "";

    if ( isset( $_GET["email"] ) ) {
        $emailToDelete = $_GET["email"];

        if ( isset( $_POST["confirmDelete"] ) ) {
            if ( deleteUser( $emailToDelete ) ) {
                header( "Location: role_management.php" );
                exit;
            } else {
                $errorMessage = "Failed to delete user";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment 5 | Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3" style="margin: 50px auto">
                <?php if ( isset( $errorMessage ) ): ?>
                <p class="text-danger my-3 text-center">
                    <?=$errorMessage;?>
                </p>
                <?php endif;?>
                <h4 class="alert alert-danger">Confirm User Deletion</h4>
                <p class="fst-italic">Are you sure you want to delete this user?</p>
                <form method="post" action="delete_user.php?email=<?=urlencode( $emailToDelete );?>">
                    <button type="submit" name="confirmDelete" class="btn btn-danger me-2 ">Confirm</button>
                    <a href="./role_management.php" class="btn btn-success">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
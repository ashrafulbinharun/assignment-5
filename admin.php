<?php

    session_start();

    if ( $_SESSION["role"] !== "admin" ) {
        header( "Location: index.php" );
        exit;
    }

    require_once './functions.php';

    sessionTimeout();

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assignment 5 | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2" style="margin: 50px auto">
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <h4>Welcome, <?=htmlspecialchars( $_SESSION["name"] );?></h4>
                    <h5 class="fst-italic">Admin</h5>
                </div>
                <p>Your Email : <strong><?=htmlspecialchars( $_SESSION["email"] );?></strong></p>
                <a href="./role_management.php" class="btn btn-outline-info me-2">
                    Role Management<i class="bi bi-person-fill-gear ms-1 "></i>
                </a>
                <a href="./logout.php" class="btn btn-primary">
                    Logout<i class="bi bi-box-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
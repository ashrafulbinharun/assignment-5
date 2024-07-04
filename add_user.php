<?php

    session_start();

    if ( $_SESSION["role"] !== "admin" ) {
        header( "Location: index.php" );
        exit;
    }

    require_once './functions.php';

    $errorMessage = [];
    $oldInput = [];

    if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {
        // if error happens keep the value
        $oldInput['username'] = $_POST['username'] ?? '';
        $oldInput['email'] = $_POST['email'] ?? '';

        $role = "manager";
        $email = $_POST["email"];
        $password = $_POST["password"];
        $username = $_POST["username"];

        // Sanitize and Validate the Name Field
        if ( empty( $username ) ) {
            $errorMessage['name'] = "Please provide a name";
        } else {
            $username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS );
        }

        // Sanitize and Validate the Email Field
        if ( empty( $email ) ) {
            $errorMessage['email'] = "Please provide an email address";
        } else {
            $email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
            if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $errorMessage['email'] = "Please provide a valid email address";
            } elseif ( duplicateEmail( $email ) ) {
                $errorMessage['email'] = "Email address already in use";
            }
        }

        // Sanitize and Validate the Password Field
        if ( empty( $password ) ) {
            $errorMessage['password'] = "Please provide a password";
        } elseif ( strlen( $password ) < 8 ) {
            $errorMessage['password'] = "Password must be at least 8 characters long.";
        } elseif ( !preg_match( '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password ) ) {
            $errorMessage['password'] = "Password should include at least one uppercase letter, one lowercase letter, one digit, and one special character.";
        } else {
            $password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS );
            $password = password_hash( $password, PASSWORD_BCRYPT );
        }

        if ( empty( $errorMessage ) ) {
            if ( registerUser( $role, $username, $email, $password ) ) {
                header( "Location: role_management.php" );
                exit;
            } else {
                $errorMessage['newuser'] = "Failed to register the user";
            }
        }
    }

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assignment 5 | Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2" style="margin: 120px auto">
                <?php if ( isset( $errorMessage['newuser'] ) ): ?>
                <p class="text-danger my-3 text-center">
                    <?=$errorMessage['newuser'];?>
                </p>
                <?php endif;?>
                <div class="card shadow px-3">
                    <div class="card-body">
                        <h4 class="text-center mb-3">Add a New User</h4>
                        <form action="<?=htmlspecialchars( $_SERVER['PHP_SELF'] )?>" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" id="username"
                                    placeholder="John Doe"
                                    value="<?php echo htmlspecialchars( $oldInput['username'] ?? '' ); ?>">
                                <?php if ( isset( $errorMessage['name'] ) ): ?>
                                <p class="text-danger mt-3 mb-4">
                                    <?=$errorMessage['name'];?>
                                </p>
                                <?php endif;?>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="name@example.com"
                                    value="<?php echo htmlspecialchars( $oldInput['email'] ?? '' ); ?>">
                                <?php if ( isset( $errorMessage['email'] ) ): ?>
                                <p class="text-danger mt-3 mb-4">
                                    <?=$errorMessage['email'];?>
                                </p>
                                <?php endif;?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="******">
                                <?php if ( isset( $errorMessage['password'] ) ): ?>
                                <p class="text-danger mt-3 mb-4">
                                    <?=$errorMessage['password'];?>
                                </p>
                                <?php endif;?>
                            </div>
                            <div class="d-grid col-4 mx-auto">
                                <button type="submit" class="btn btn-primary mt-3">
                                    Add User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <a href="./role_management.php" class="btn btn-outline-info mt-3">
                    Go Back <i class="bi bi-house-gear-fill"></i>
                </a>
            </div>
        </div>
    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>
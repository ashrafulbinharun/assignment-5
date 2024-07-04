<?php

    session_start();

    if ( isset( $_SESSION["email"] ) ) {
        header( "Location: index.php" );
        exit;
    }

    require_once './functions.php';

    $errorMessage = [];
    $oldInput = [];

    if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {
        // if error happens keep the value
        $oldInput['email'] = $_POST['email'] ?? '';

        $email = $_POST["email"];
        $password = $_POST["password"];

        // Sanitize and Validate the Email Field
        if ( empty( $email ) ) {
            $errorMessage['email'] = "Please provide an email address";
        } else {
            $email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
            if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                $errorMessage['email'] = "Please provide a valid email address";
            }
        }

        // Sanitize and Validate the Password Field
        if ( empty( $password ) ) {
            $errorMessage['password'] = "Please provide a password";
        } elseif ( strlen( $password ) < 8 ) {
            $errorMessage['password'] = "Password must be at least 8 characters long.";
        }

        if ( empty( $errorMessage ) ) {
            if ( loginUser( $email, $password ) ) {
                header( "Location: index.php" );
                exit;
            } else {
                $errorMessage['auth'] = "Invalid email or password";
            }
        }
    }

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assignment 5 | Sine In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2" style="margin: 75px auto">
                <?php if ( isset( $errorMessage['auth'] ) ): ?>
                <p class="text-danger my-3 text-center">
                    <?=$errorMessage['auth'];?>
                </p>
                <?php endif;?>
                <div class="card shadow px-3 mb-4">
                    <div class="card-body">
                        <h4 class="text-center mb-4">Please Login to Proceeds</h4>
                        <form action="<?=htmlspecialchars( $_SERVER['PHP_SELF'] )?>" method="POST">
                            <div class="row g-3 align-items-center px-2">
                                <div class="col-3">
                                    <label for="email" class="col-form-label">Email address</label>
                                </div>
                                <div class="col-9">
                                    <input type="email" id="email" class="form-control" name="email"
                                        placeholder="name@example.com"
                                        value="<?php echo htmlspecialchars( $oldInput['email'] ?? '' ); ?>">
                                    <?php if ( isset( $errorMessage['email'] ) ): ?>
                                    <p class="text-danger mt-3 mb-4">
                                        <?=$errorMessage['email'];?>
                                    </p>
                                    <?php endif;?>
                                </div>
                                <div class="col-3">
                                    <label for="password" class="col-form-label">Password</label>
                                </div>
                                <div class="col-9">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="******">
                                    <?php if ( isset( $errorMessage['password'] ) ): ?>
                                    <p class="text-danger mt-3 mb-4">
                                        <?=$errorMessage['password'];?>
                                    </p>
                                    <?php endif;?>
                                </div>
                                <div class="col-auto mx-auto">
                                    <button type="submit" class="btn btn-info ">Log In</button>
                                </div>
                            </div>
                        </form>

                        <div style="display: flex; justify-content: center; align-items: center;margin: 20px 0 10px;">
                            <span class="me-2">Not a User?</span>
                            <a href="./sine_up.php" class="btn btn-warning">Register Now</a>
                        </div>

                    </div>
                </div>
                <h6 class="alert alert-info text-center">admin: admin@gmail.com | password : A@a12345</h6>
                <h6 class="alert alert-info text-center">manager: manager@gmail.com | password : M@m12345</h6>
                <h6 class="alert alert-info text-center">user: user@gmail.com | password : U@u12345</h6>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>
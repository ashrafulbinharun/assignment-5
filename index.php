<?php

session_start();

if ( !isset( $_SESSION["email"] ) ) {
    header( "Location: login.php" );
} elseif ( $_SESSION["role"] == "user" ) {
    header( "Location: user.php" );
} elseif ( $_SESSION["role"] == "manager" ) {
    header( "Location: manager.php" );
} elseif ( $_SESSION["role"] == "admin" ) {
    header( "Location: admin.php" );
}

?>
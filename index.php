<?php

session_start();

// Check if the user is logged in
if ( !isset( $_SESSION["email"] ) ) {
    header( "Location: login.php" );
    exit;
} elseif ( $_SESSION["role"] === "user" ) {
    header( "Location: user.php" );
    exit;
} elseif ( $_SESSION["role"] === "manager" ) {
    header( "Location: manager.php" );
    exit;
} elseif ( $_SESSION["role"] === "admin" ) {
    header( "Location: admin.php" );
    exit;
}
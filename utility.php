<?php

/**
 * Utility function to read user data from the file.
 *
 * @return array
 */
function readUserData() {
    return file( DB, FILE_IGNORE_NEW_LINES );
}

/**
 * Utility function to write user data to the file.
 *
 * @param array $userData
 */
function writeUserData( $userData ) {
    $write = join( PHP_EOL, $userData ) . PHP_EOL;
    file_put_contents( DB, $write );
}

/**
 * Utility function to parse user information from a line.
 *
 * @param string $line The line containing user information.
 * @return array The parsed user information.
 */
function parseUserInfo( $line ) {
    $userDetails = explode( ", ", $line );
    return [
        'role'     => trim( $userDetails[0] ),
        'username' => trim( $userDetails[1] ),
        'email'    => trim( $userDetails[2] ),
    ];
}

/**
 * Utility function to parse user login information from a line.
 *
 * @param string $line The line containing user login information.
 * @return array|boolean The parsed user login information.
 */
function parseUserLoginInfo( $line ) {
    $userDetails = explode( ", ", $line );
    return [
        'role'     => trim( $userDetails[0] ),
        'username' => trim( $userDetails[1] ),
        'email'    => trim( $userDetails[2] ),
        'password' => trim( $userDetails[3] ),
    ];
}
<?php

require_once './utility.php';

define( "DB", __DIR__ . "/data/auth.txt" );

/**
 * Registers a new user by writing their details to the auth file.
 *
 * @param string $role
 * @param string $username
 * @param string $email
 * @param string $password
 * @return bool
 */
function registerUser( $role, $username, $email, $password ) {
    $fp = fopen( DB, "a" );
    fwrite( $fp, "{$role}, {$username}, {$email}, {$password}\n" );
    fclose( $fp );
    return true;
}

/**
 * Checks if an email is already registered.
 *
 * @param string $email
 * @return bool
 */
function duplicateEmail( $email ) {
    $data = readUserData();

    foreach ( $data as $line ) {
        $userDetails = parseUserInfo( $line );
        if ( $userDetails['email'] === $email ) {
            return true;
        }
    }

    return false;
}

/**
 * Logs in a user by verifying their email and password.
 *
 * @param string $email
 * @param string $password
 * @return bool
 */
function loginUser( $email, $password ) {
    $data = readUserData();

    foreach ( $data as $line ) {
        $user = parseUserLoginInfo( $line );
        if ( $user['email'] === $email && password_verify( $password, $user['password'] ) ) {
            $_SESSION["role"] = $user['role'];
            $_SESSION["name"] = $user['username'];
            $_SESSION["email"] = $user['email'];
            return true;
        }
    }
    return false;
}

/**
 * Handles session timeout by checking the last activity time.
 *
 * @param int $timeout (default: 1800 seconds)
 */
function sessionTimeout( $timeout = 1800 ) { // for demonstration purpose it's 30 minutes
    if ( isset( $_SESSION['last_activity'] ) &&
        ( time() - $_SESSION['last_activity'] ) > $timeout ) {
        session_unset();
        session_destroy();
        header( "Location: index.php" );
        die();
    } else {
        $_SESSION['last_activity'] = time();
    }
}

/**
 * Retrieves all users from the auth file.
 *
 * @return array
 */
function allUser() {
    $userData = readUserData();
    $result = [];

    foreach ( $userData as $line ) {
        $userDetails = parseUserInfo( $line );
        $result[] = [
            'role'     => $userDetails['role'],
            'username' => $userDetails['username'],
            'email'    => $userDetails['email'],
        ];
    }
    return $result;
}

/**
 * Retrieves user details by their email.
 *
 * @param string $email
 * @return array|bool
 */
function editUser( $email ) {
    $userData = readUserData();

    foreach ( $userData as $line ) {
        $userDetails = parseUserInfo( $line );
        if ( $userDetails['email'] === $email ) {
            return [
                'role'  => $userDetails['role'],
                'name'  => $userDetails['username'],
                'email' => $userDetails['email'],
            ];
        }
    }

    return false;
}

/**
 * Updates a user's details.
 *
 * @param string $role
 * @param string $username
 * @param string $email
 * @return bool
 */
function updateUser( $role, $username, $email ) {
    $userData = readUserData();

    foreach ( $userData as &$line ) {
        $userDetails = parseUserLoginInfo( $line );
        if ( $userDetails['email'] === $email ) {
            $userDetails['role'] = $role;
            $userDetails['username'] = $username;
            $line = join( ", ", [$userDetails['role'], $userDetails['username'], $userDetails['email'], $userDetails['password']] );
            break;
        }
    }

    writeUserData( $userData );
    return true;
}

/**
 * Deletes a user by their email.
 *
 * @param string $email
 * @return bool
 */
function deleteUser( $email ) {
    $userData = readUserData();

    foreach ( $userData as $userInfo => $line ) {
        $userDetails = parseUserInfo( $line );
        if ( $userDetails['email'] === $email ) {
            unset( $userData[$userInfo] );
            break;
        }
    }

    writeUserData( $userData );
    return true;
}
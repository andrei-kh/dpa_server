<?php

define("PATTERN_USERNAME", "/^[a-zA-Z0-9_]+$/");

function errorMessage(string $location, string $error = ""): void
{
    $location = "location: " . $location;
    if (!empty($error))
        $location .= "?error=" . $error;
    header($location);
    exit();
}

function areEmpty(string ...$fields): bool
{
    foreach ($fields as $field)
        if (empty($field))
            return true;

    return false;
}

function invalidUsername(string $username): bool
{
    if (preg_match(PATTERN_USERNAME, $username))
        return false;
    return true;
}

function diffPasswords(string $pass1, string $pass2): bool
{
    if ($pass1 === $pass2)
        return false;
    return true;
}

/**
 * @return array|bool 
 * false if user doesn't exist.
 * array of rows with $username as username otherwise.
 * */
function userExists(mysqli $conn, string $username, string $location): array | bool
{
    $sql = "SELECT * FROM users WHERE username = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql))
        errorMessage($location, "stmt_prepare_error");

    mysqli_stmt_bind_param($stmt, "s", $username);

    if (!mysqli_stmt_execute($stmt))
        errorMessage($location, "stmt_execute_error");

    $res = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    if ($row = mysqli_fetch_assoc($res))
        return $row;

    return false;
}

function createUser(mysqli $conn, string $username, string $password, string $name): void
{
    $sql = "INSERT INTO users (username, password, name) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql))
        errorMessage("../register.php", "stmt_prepare_error");

    $h_password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $username, $h_password, $name);

    if (!mysqli_stmt_execute($stmt))
        errorMessage("../register.php", "stmt_execute_error");

    mysqli_stmt_close($stmt);

    loginUser($conn, $username, $password);
}

function loginUser(mysqli $conn, string $username, string $password): void
{
    $usernameExists = userExists($conn, $username, "../login.php");

    if ($usernameExists === false)
        errorMessage("../login.php", "wrong_username");

    $h_password = $usernameExists["password"];

    if (password_verify($password, $h_password)) {
        session_start();
        $_SESSION["id"] = $usernameExists["id"];
        $_SESSION["username"] = $usernameExists["username"];
        $_SESSION["name"] = $usernameExists["name"];
        errorMessage("../index.php");
    } else {
        errorMessage("../login.php", "wrong_password");
    }
}

<?php

function createUser(mysqli $conn, string $username, string $password, string $name): ?string
{
    $sql = "INSERT INTO users (username, password, name) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql))
        return "db_error";

    $h_password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $username, $h_password, $name);

    if (!mysqli_stmt_execute($stmt))
    {
        switch (mysqli_errno($conn)) {
            case 1062:
                return "user_exists";
            
            default:
                return "db_error";
        }
    }

    mysqli_stmt_close($stmt);
    
    return null;
}

/**
 * @return array|bool 
 * false if user doesn't exist.
 * array of rows with $username as username otherwise.
 * */
function userExists(mysqli $conn, string $username): array | bool
{
    $sql = "SELECT * FROM users WHERE username = ?;";
    $stmt = mysqli_stmt_init($conn);

    mysqli_stmt_prepare($stmt, $sql);

    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

    if ($row = mysqli_fetch_assoc($res))
        return $row;

    return false;
}

function loginUser(mysqli $conn, string $username, string $password): ?string
{
    try {
        $usernameExists = userExists($conn, $username);
    } catch (\Throwable $e) {
        return "db_error";
    }

    if ($usernameExists === false)
        return "wrong_username";

    $h_password = $usernameExists["password"];

    if (password_verify($password, $h_password)) {
        session_start();
        $_SESSION["id"] = $usernameExists["id"];
        $_SESSION["username"] = $usernameExists["username"];
        $_SESSION["name"] = $usernameExists["name"];
    } else {
        return "wrong_password";
    }

    return null;
}

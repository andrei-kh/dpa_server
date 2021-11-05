<?php
define("DUPLICATE_ERROR_PATTERN", "/users\.(?<type>[a-z]+)/");
define("BYTE_LENGTH", 50);

function createUser(mysqli $conn, string $username, string $password, string $email, string $name): ?string
{
    $sql = "INSERT INTO users (username, password, email, email_token, name) VALUES (?, ?, ?, ?, ?);";
    $stmt = $conn->stmt_init();

    if (!$stmt->prepare($sql))
        return "db_error";

    $h_password = password_hash($password, PASSWORD_DEFAULT);
    $email_token = strtr(base64_encode(random_bytes(BYTE_LENGTH)), '+/=', '._-');

    $stmt->bind_param("sssss", $username, $h_password, $email, $email_token, $name);

    if (!$stmt->execute()) {
        switch ($conn->errno) {
            case 1062:
                preg_match(DUPLICATE_ERROR_PATTERN, $conn->error, $matches);
                return $matches["type"] . "_exists";
            default:
                return "db_error";
        }
    }

    $stmt->close();
    // add error
    send_verification_email($email, $name, $email_token);

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
    $stmt = $conn->stmt_init();

    $stmt->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $res = $stmt->get_result();
    if ($res->num_rows > 1) {
        throw new Exception("More than one username exists.");
    }

    $stmt->close();

    if ($row = $res->fetch_assoc()) {

        return $row;
    }

    return false;
}

function loginUser(mysqli $conn, string $username, string $password): ?string
{
    try {
        $row = userExists($conn, $username);
    } catch (\Throwable $e) {
        echo $e->getMessage();
        return "db_error";
    }

    if ($row === false)
        return "wrong_username";

    if (password_verify($password, $row["password"])) {
        unset($row["password"]);
        set_session($row);
    } else {
        return "wrong_password";
    }

    return null;
}

function loginUserInjection(mysqli $conn, string $username, string $password): ?string
{
    $sql = "SELECT * FROM users WHERE username = '"  . $username . "' and password = '" . $password . "';";

    // INJECTION: login: any_login and password = ' or ''='
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        unset($row["password"]);
        set_session($row);
    } else {
        return "wrong_password";
    }

    return null;
}

// REFACTOR THIS!!!
function verify_email(mysqli $read_conn, mysqli $write_conn, string $token): ?string
{
    $sql = "SELECT * FROM users WHERE email_token= ? LIMIT 1";
    $stmt = $read_conn->stmt_init();

    try {
        $stmt->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $res = $stmt->get_result();
        $stmt->close();

        if ($row = $res->fetch_assoc()) {
            $stmt = $write_conn->stmt_init();
            $sql = "UPDATE users SET  email_status = 'approved' WHERE id = ?";
            $stmt->prepare($sql);
            $stmt->bind_param("s", $row['id']);
            $stmt->execute();
            $stmt->close();
            set_session(array('email_status' => 'approved'));
        } else {
            return "invalid_token";
        }
    } catch (\Throwable $e) {
        return "db_error";
    }

    return null;
}

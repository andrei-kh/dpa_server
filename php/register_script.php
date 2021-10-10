<?php

require_once "include/utils.inc.php";
require_once "include/db.inc.php";
require_once "include/connect.inc.php";

if (isset($_POST["submit"])) {
    $captcha = $_POST["captcha"];
    if(!valid_captcha($captcha))
        errorMessage("../register.php", "invalid_captcha");

    $username = $_POST["username"];
    $password = $_POST["password"];
    $password_conf = $_POST["password_conf"];
    $name = $_POST["name"];
    
    if (areEmpty($username, $password, $password_conf, $name))
        errorMessage("../register.php", "empty_input");

    if (invalidUsername($username))
        errorMessage("../register.php", "invalid_username");

    if (diffPasswords($password, $password_conf))
        errorMessage("../register.php", "different_passwords");

    $write_conn = writer_connect();
    $error = createUser($write_conn, $username, $password, $name);

    if (isset($error))
        errorMessage("../register.php", $error);
    else {
        $read_conn = reader_connect();
        loginUser($read_conn, $username, $password);
        errorMessage("../index.php");
    }
} else {
    errorMessage("../register.php");
}

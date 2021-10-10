<?php
require_once "include/utils.inc.php";
require_once "include/db.inc.php";
require_once "include/connect.inc.php";

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];


    if (areEmpty($username, $password))
        errorMessage("../login.php", "empty_input");

    if (invalidUsername($username))
        errorMessage("../login.php", "invalid_username");

    $read_conn = reader_connect();
    $error = loginUser($read_conn, $username, $password);

    if (isset($error))
        errorMessage("../login.php", $error);
    else
        errorMessage("../index.php");
} else {
    errorMessage("../login.php");
}

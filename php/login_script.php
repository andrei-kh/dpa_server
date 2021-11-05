<?php
require_once "include/utils.inc.php";
require_once "include/db.inc.php";
require_once "include/connect.inc.php";

if (isset($_POST["submit"]) || isset($_POST["submit-injection"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];


    if (areEmpty($username, $password))
        errorMessage("../login.php", "empty_input");

    if (invalidUsername($username) && isset($_POST["submit"]))
        errorMessage("../login.php", "invalid_username");

    $read_conn = reader_connect();
    
    if(isset($_POST["submit"]))
        $error = loginUser($read_conn, $username, $password);
    else
        $error = loginUserInjection($read_conn, $username, $password);

    if (isset($error))
        errorMessage("../login.php", $error);
    else
        errorMessage("../index.php");
} else {
    errorMessage("../login.php");
}

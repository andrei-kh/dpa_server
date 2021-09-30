<?php

require_once "include/utils.inc.php";

if (isset($_POST["submit"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);


    if (areEmpty($username, $password))
        errorMessage("../login.php", "empty_input");

    if (invalidUsername($username))
        errorMessage("../login.php", "invalid_username");

    require_once "include/connect.inc.php";

    $error = loginUser($read_conn, $username, $password);

    if(isset($error))
        errorMessage("../login.php", $error);
    else
        errorMessage("../index.php");
}
else{
    errorMessage("../login.php");
}

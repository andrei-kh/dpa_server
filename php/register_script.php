<?php

require_once "include/utils.inc.php";

if (isset($_POST["submit"])) {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $password_conf = trim($_POST["password_conf"]);
    $name = trim($_POST["name"]);

    if (areEmpty($username, $password, $password_conf, $name))
        errorMessage("../register.php", "empty_input");

    if (invalidUsername($username))
        errorMessage("../register.php", "invalid_username");

    if (diffPasswords($password, $password_conf))
        errorMessage("../register.php", "different_passwords");

    require_once "include/connect.inc.php";

    if (userExists($connection, $username, "../register.php"))
        errorMessage("../register.php", "user_exists");

    createUser($connection, $username, $password, $name);
} else {
    errorMessage("../register.php");
}

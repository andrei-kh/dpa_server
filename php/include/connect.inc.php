<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'rootpass');
define('DB_NAME', 'serverdb');

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($connection == false) {
    die("Connection failed: " . mysqli_connect_error());
}

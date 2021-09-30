<?php
define('DB_SERVER', get_cfg_var("dpa_server.cfg.DB_SERVER"));
define('DB_NAME', get_cfg_var("dpa_server.cfg.DB_NAME"));
define('DB_READER_USER', get_cfg_var("dpa_server.cfg.DB_READER_USER"));
define('DB_READER_PASS', get_cfg_var("dpa_server.cfg.DB_READER_PASS"));
define('DB_WRITER_USER', get_cfg_var("dpa_server.cfg.DB_WRITER_USER"));
define('DB_WRITER_PASS', get_cfg_var("dpa_server.cfg.DB_WRITER_PASS"));

$read_conn = mysqli_connect(DB_SERVER, DB_READER_USER, DB_READER_PASS, DB_NAME);
$write_conn = mysqli_connect(DB_SERVER, DB_WRITER_USER, DB_WRITER_PASS, DB_NAME);

if ($read_conn == false || $write_conn == false) {
    die("Connection failed: " . mysqli_connect_error());
}


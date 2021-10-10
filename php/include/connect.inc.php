<?php
function reader_connect(): mysqli
{
    $db_server = get_cfg_var("dpa_server.cfg.DB_SERVER");
    $db_name = get_cfg_var("dpa_server.cfg.DB_NAME");
    $db_reader_user = get_cfg_var("dpa_server.cfg.DB_READER_USER");
    $db_reader_pass = get_cfg_var("dpa_server.cfg.DB_READER_PASS");

    $read_conn = mysqli_connect($db_server, $db_reader_user, $db_reader_pass, $db_name);

    if ($read_conn == false)
        die("Connection failed: " . mysqli_connect_error());

    return $read_conn;
}

function writer_connect(): mysqli
{
    $db_server = get_cfg_var("dpa_server.cfg.DB_SERVER");
    $db_name = get_cfg_var("dpa_server.cfg.DB_NAME");
    $db_writer_user = get_cfg_var("dpa_server.cfg.DB_WRITER_USER");
    $db_writer_pass = get_cfg_var("dpa_server.cfg.DB_WRITER_PASS");

    $write_conn = mysqli_connect($db_server, $db_writer_user, $db_writer_pass, $db_name);

    if ($write_conn == false)
        die("Connection failed: " . mysqli_connect_error());

    return $write_conn;
}

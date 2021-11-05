<?php
function reader_connect(): mysqli | bool
{
    $db_server = get_cfg_var("dpa_server.cfg.DB_SERVER");
    $db_name = get_cfg_var("dpa_server.cfg.DB_NAME");
    $db_reader_user = get_cfg_var("dpa_server.cfg.DB_READER_USER");
    $db_reader_pass = get_cfg_var("dpa_server.cfg.DB_READER_PASS");

    return get_connection($db_server, $db_reader_user, $db_reader_pass, $db_name);
}

function writer_connect(): mysqli | bool
{
    $db_server = get_cfg_var("dpa_server.cfg.DB_SERVER");
    $db_name = get_cfg_var("dpa_server.cfg.DB_NAME");
    $db_writer_user = get_cfg_var("dpa_server.cfg.DB_WRITER_USER");
    $db_writer_pass = get_cfg_var("dpa_server.cfg.DB_WRITER_PASS");

    return get_connection($db_server, $db_writer_user, $db_writer_pass, $db_name);
}

function get_connection($db_server, $db_user, $db_pass, $db_name): mysqli | null
{
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if ($conn == false)
        die("Connection failed: " . mysqli_connect_error());

    return $conn;
}
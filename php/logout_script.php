<?php

require_once "include/utils.inc.php";

session_start();
session_unset();
session_destroy();

errorMessage("../index.php");
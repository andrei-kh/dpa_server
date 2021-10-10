<?php

define("PATTERN_USERNAME", "/^[a-zA-Z0-9_]+$/");

function errorMessage(string $location, string $error = ""): void
{
    $location = "location: " . $location;
    if (!empty($error))
        $location .= "?error=" . $error;
    header($location);
    exit();
}

function areEmpty(string ...$fields): bool
{
    foreach ($fields as $field)
        if (empty($field))
            return true;

    return false;
}

function invalidUsername(string $username): bool
{
    if (preg_match(PATTERN_USERNAME, $username))
        return false;
    return true;
}

function diffPasswords(string $pass1, string $pass2): bool
{
    if ($pass1 === $pass2)
        return false;
    return true;
}

function set_session(string $key, string $value): void
{
    session_start();
    $_SESSION[$key] = $value;
}

function get_session(string $key)
{
    session_start();
    if (isset($key) && isset($_SESSION[$key]))
        return $_SESSION[$key];
    return false;
}

function hex2RGB($hexStr)
{
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
    $rgbArray = array();

    $colorVal = hexdec($hexStr);
    $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
    $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
    $rgbArray['blue'] = 0xFF & $colorVal;

    return $rgbArray;
}

function valid_captcha($code): bool
{
    $valid_code = get_session("captcha");

    return $valid_code == $code;
}

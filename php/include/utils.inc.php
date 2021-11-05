<?php

define("PATTERN_USERNAME", "/^[a-zA-Z0-9_]+$/");

function errorMessage(string $location, string $error = ""): void
{
    $location = "location: " . $location;
    if (!empty($error))
        set_session(['error' => $error]);
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

function set_session(array $values): void
{
    if (!isset($_SESSION)) {
        session_start();
    }
    foreach ($values as $key => $value) {
        $_SESSION[$key] = $value;
    }
}

function get_session(string $key)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($key) && isset($_SESSION[$key])) {
        $result = $_SESSION[$key];
        return $result;
    }
    return false;
}

function unset_session(string $key): void
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($key))
        unset($_SESSION[$key]);
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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';


function send_verification_email(string $email, string $name, string $token): void
{
    $mail_vars = array('name' => $name, 'token' => $token);

    $message = file_get_contents(__DIR__ . "/../../html/verification_email_template.html", true);

    foreach ($mail_vars as $key => $value) {
        $message = str_replace('{{ ' . $key . ' }}', $value, $message);
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;

        $mail->Username = get_cfg_var("dpa_server.cfg.EMAIL_USERNAME");;
        $mail->Password = get_cfg_var("dpa_server.cfg.EMAIL_PASSWORD");;

        $mail->setFrom($mail->Username);
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Email Verification";
        $mail->Body = $message;

        $mail->send();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

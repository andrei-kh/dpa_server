<?php
require_once "include/captcha.inc.php";
$captcha = new Captcha(5, 110, 60);
$captcha->render_captcha();

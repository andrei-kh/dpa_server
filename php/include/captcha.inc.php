<?php
require_once "utils.inc.php";

define("CAPTCHA_BACKGROUND", "#242424");
define("CAPTCHA_TEXT", "#d8dfda");
define("CAPTCHA_FONT", "/fonts/lemon.ttf");
define("CAPTCHA_FONT_SIZE", 30);

class Captcha
{
    private array $background_color;
    private array $text_color;
    private int $length;
    private string $font;
    private int $font_size;
    private int $width;
    private int $height;

    function __construct(
        int $length,
        int $width,
        int $height,
        string $background_color = CAPTCHA_BACKGROUND,
        string $text_color = CAPTCHA_TEXT,
        string $font = CAPTCHA_FONT,
        int $font_size = CAPTCHA_FONT_SIZE
    ) {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->background_color = hex2RGB($background_color);
        $this->text_color = hex2RGB($text_color);
        $this->font = $_SERVER['DOCUMENT_ROOT'] . $font;
        $this->font_size = $font_size;
    }

    private function get_code(): string
    {
        $md5_hash = md5(random_bytes(64));
        return substr($md5_hash, 0, $this->length);
    }

    private function create_image(string $code, int $width, int $height): GdImage
    {
        $image = imagecreatetruecolor($width, $height);
        $background_color = imagecolorallocate(
            $image,
            $this->background_color['red'],
            $this->background_color['blue'],
            $this->background_color['green']
        );
        $text_color = imagecolorallocate(
            $image,
            $this->text_color['red'],
            $this->text_color['blue'],
            $this->text_color['green']
        );


        $angle = rand(-15, 15);
        imagefill($image, 0, 0, $background_color);
        $text_box = imagettfbbox($this->font_size, $angle, $this->font, $code);
        $x = ($width - $text_box[4]) / 2;
        $y = ($height - $text_box[5]) / 2;
        imagettftext($image, $this->font_size, $angle, $x, $y, $text_color, $this->font, $code);

        return $image;
    }

    function render_captcha(): void
    {
        $captcha_code = $this->get_code();
        set_session(['captcha' => $captcha_code]);
        $image = $this->create_image($captcha_code, $this->width, $this->height);

        header("Content-type: image/jpeg");
        imagejpeg($image);
        imagedestroy($image);
    }
}

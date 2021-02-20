<?php
/**
 * Created by PhpStorm.
 * User: Ryaan
 * Date: 10/01/18
 * Time: 14:37
 */

namespace App\Service;


use ReCaptcha\ReCaptcha;

class CaptchaValidator
{
    private $key;
    private $secret;

    public function __construct($key, $secret)
    {
        $this->key = '6Lc5CVgaAAAAAJL8V2UR9DCU3k8yUT0PNgSkmJbm';
        $this->secret = '6Lc5CVgaAAAAAHrbgFk0DeUN1HtSWdawsxoCG28l';
    }

    public function validateCaptcha($gRecaptchaResponse)
    {
        $recaptcha = new ReCaptcha($this->secret);
        $resp = $recaptcha->verify($gRecaptchaResponse);
        return $resp->isSuccess();
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}

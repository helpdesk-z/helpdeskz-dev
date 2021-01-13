<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use Config\Services;

class reCAPTCHA
{
    public function display()
    {
        $settings = Services::settings();
        if(!$settings->config('recaptcha'))
        {
            return null;
        }
        $html = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
        $html .= '<div class="form-group">';
        $html .= form_label(lang('Client.form.captchaVerification').' <span class="text-danger">*</span>');
        $html .= '<div class="g-recaptcha" data-sitekey="'.$settings->config('recaptcha_sitekey').'"></div>';
        $html .= '</div>';
        return $html;
    }

    public function validate()
    {
        $settings = Services::settings();
        if(!$settings->config('recaptcha')){
            return true;
        }
        $request = Services::request();
        $recaptcha = new \ReCaptcha\ReCaptcha($settings->config('recaptcha_privatekey'));
        $resp = $recaptcha->verify($request->getPost('g-recaptcha-response'), $request->getIPAddress());
        if($resp->isSuccess()){
            return true;
        }
        return false;
    }
}
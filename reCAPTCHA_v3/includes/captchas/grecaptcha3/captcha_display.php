<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://www.phpfusion.com/
+--------------------------------------------------------+
| Filename: captcha_display.php
| Author: RobiNN
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
defined('IN_FUSION') || exit;

$_CAPTCHA_HIDE_INPUT = TRUE;

if (!function_exists('display_captcha')) {
    function display_captcha($options = []) {
        $default_options = [
            'captcha_id' => 'g-recaptcha'
        ];

        $options += $default_options;

        if (defined('GRECAPTCHA3_EXIST')) {
            $rc3_settings = get_settings('grecaptcha3');

            //add_to_css('.grecaptcha-badge{display:none!important;}');

            if (!empty($rc3_settings['public_key'])) {
                add_to_head('<script src="https://www.google.com/recaptcha/api.js?render='.$rc3_settings['public_key'].'"></script>');
                add_to_head('<script>grecaptcha.ready(function () {
                    grecaptcha.execute("'.$rc3_settings['public_key'].'", { action: "register" }).then(function (token) {
                        document.getElementById("'.$options['captcha_id'].'").value = token;
                    });
                });</script>');
            }

            return '<input type="hidden" name="g-recaptcha-response" id="'.$options['captcha_id'].'">';
        }

        return NULL;
    }
}
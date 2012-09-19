<?php

require_once dirname(__FILE__) . '/../main.php';

if ( ! is_ajax() or ! is_post()) die('bye');

$invited = 0;
if (isset($_COOKIE['prewh_email']) and existing_email($email = $_COOKIE['prewh_email'])) {
    $invited = invited_friendcount($email);
}

finish(array('invited' => $invited));




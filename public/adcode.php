<?php

require_once dirname(__FILE__) . '/../main.php';

if ( ! is_ajax() or ! is_get()) die('');

if ( ! isset($_COOKIE['prewh_email']) or ! isset($_GET['a'])) exit;



if ( ! ($sub = subscriber($_COOKIE['prewh_email'])) ) exit;

set_ad_code_to_user($sub['email'],$_GET['a']);

exit;

<?php

require_once dirname(__FILE__) . '/../main.php';

if ( ! is_ajax()) die('this is an ajax endpoint !');

if ( ! isset($_COOKIE['email'])) {
    redirect(BASEURL);finish();
}



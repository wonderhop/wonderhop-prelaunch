<?php

require_once dirname(__FILE__) . '/../main.php';


if ( ! is_post() or ! is_ajax() or ! isset($_POST['code']) or empty($_POST['code']) )
    die('Invalid Request !');

//if (slideshowed($_POST['code'])) {
//    finish(array('success' : true ));
//} else {
//    finish(array('success' : false ));
//}

slideshowed($_POST['code']);
exit;

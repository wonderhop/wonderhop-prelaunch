<?php

require_once dirname(__FILE__) . '/../main.php';

if ( ! is_ajax()) die('this is an ajax endpoint !');

 $mail_list = explode(",", $_POST['emails']);
 foreach($mail_list as $email) {
    send_invitation_email($email);
 }
 

if ( ! isset($_COOKIE['email'])) {
   
    redirect(BASEURL);finish();
}



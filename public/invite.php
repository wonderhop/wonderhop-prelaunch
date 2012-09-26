<?php

require_once dirname(__FILE__) . '/../main.php';

if ( ! is_ajax() or ! is_post()) die('');

if ( ! isset($_COOKIE['prewh_email'])) {
    redirect(BASEURL);finish();
}

$inviter = subscriber($_COOKIE['prewh_email']);

if ( ! isset($_POST['emails']) or ! $inviter) finish(array());

$mail_list = explode(",", $_POST['emails']);
foreach($mail_list as $email) {
    $address = (($from = strpos($email,'<')) !== false and ($to = strrpos($email,'>')) !== false)
             ? substr($email,$from+1, $to-$from-1)
             : $email;
    error_log($address);
    if (existing_email($address)) continue;
    newemail_save($address, $inviter['id'], true);
    $sub = subscriber($address,'*');
    email_is_invitation($sub['email']);
    send_invitation_email($sub['email'], $sub['confirm_token'], $inviter['email'], $inviter['personal_token']);
}

finish(array('sent' => 0));



<?php


require_once dirname(__FILE__) . '/../main.php';


if ( ! is_ajax()) die('Endpoint not supported !');

if (is_post() and isset($_POST['email']) and valid_email($email = $_POST['email'])) {
    //finish(array( 'error' => existing_email($email)));
    if ($id = existing_email($email)) finish(array(
        'success' => true,
        'existing' => true,
        'subscriber' => subscriber($id),
        'personal_link' => personal_link($id, true),
    ));
    elseif( newemail_save($email, (isset($_POST['inviter']) and $_POST['inviter']) ? $_POST['inviter'] : NULL) ) finish(array(
        'success' => true,
        'existing' => false,
        'subscriber' => subscriber($email),
        'personal_link' => personal_link($email, true),
    )); 
}

finish(array('error' => 'Invalid request !'));

<?php

function is_post()
{
    return ! empty($_POST) and empty($_GET);
}

function is_get()
{
    return ! empty($_GET) and empty($_POST);
}

function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) and (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}
function finish($inject = NULL)
{
    if ($inject !== NULL) {
        $response = $inject;
    } else {
        global $response;
    }
    echo json_encode($response);
    exit;
}

function redirect($url = false)
{
    $url = $url ? $url : 0;
    global $response;
    $response['redirect'] = $url;
}

function db()
{
    global $db;
    return $db;
}

function valid_email($email)
{
    return (is_string($email) and strpos($email,'@')); // this is intentional 
}


function existing_email($email)
{
    $q = db()->prepare("select id from subscribers where email = ?;");
    return ($q->execute(array(trim(strtolower($email)))) and ($res = $q->fetchAll())) ? $res[0]['id'] : false ;
}

function existing_referrer($token)
{
    $q = db()->prepare("select id from subscribers where personal_token = ?;");
    return ($q->execute(array(trim($token))) and ($res = $q->fetchAll())) ? $res[0]['id'] : false ;
}
/*
function existing_subscriber($email_or_id_or_token)
{
    $field = is_numeric($email_or_id_or_token) ? 'id' : (strpos($email_or_id_or_token,'@') ? 'email' : 'personal_token');
    $val = trim(($field === 'personal_token') ? $email_or_id_or_token : strtolower($email_or_id_or_token));
    $q = db()->prepare("select id from subscribers where $field = ?;");
    return ($q->execute(array($val)) and ($res = $q->fetchAll(PDO::FETCH_ASSOC))) ? $res[0]['id'] : false ;
}
*/
function newemail_save($email, $invited_by = NULL)
{
    error_log($invited_by);
    list($fields,$values, $vph, $inviter) = 
      array('(email,personal_token,confirm_token', array($email = trim(strtolower($email)),gen_personal_token($email), gen_confirm_token($email)), '(?,?,?', false);
    if ($invited_by) {
        $inviter = is_numeric($invited_by)
                 ? $invited_by
                 : (strpos($invited_by,'@') ? existing_email($invited_by) : existing_referrer($invited_by));
        error_log($inviter);
    }
    if ($inviter and ($inviter = subscriber($inviter))) {
        $fields .= ',invited_by';
        $vph .= ',?';
        $values[] = $inviter['id'];
        send_confirm_email($email, $values[2]); 
    }
    $fields .=  ')';
    $vph .=  ')';
    $q = db()->prepare("insert into subscribers{$fields} values{$vph}");
    return $q->execute($values);
}

function subscriber($email_or_id_or_token, $fields = '`id`,`email`,`personal_token`,`invited_by`,`confirmed`,`slideshowed`')
{
    $id = is_numeric($email_or_id_or_token)
        ? trim($email_or_id_or_token)
        : (strpos($email_or_id_or_token,'@') ? existing_email($email_or_id_or_token) : existing_referrer($email_or_id_or_token));
    if ( ! $id) return NULL;
    $q = db()->prepare("select {$fields} from subscribers where `id` = ? ;");
    return ($q->execute(array($id)) and ($res = $q->fetchAll(PDO::FETCH_ASSOC))) ? $res[0] : false ;
}


function send_confirm_email($email, $token)
{
    mail($email, 'Email Confirmation', BASEURL . "?c=$token");
}


function personal_link($email, $as_get_param = false)
{
    $sub = subscriber($email);
    return BASEURL . ($as_get_param ? '?r=' : '') . $sub['personal_token'];
}

function gen_personal_token($email)
{
    //return md5(B_SALT . $email . E_SALT);
    return genRefCode();
}

function gen_confirm_token($email)
{
    return md5(E_SALT . $email . B_SALT);
}


function _generateReferralCode($len = 6)
{
	$hex = md5("referral" . uniqid("", true));
	$pack = pack('H*', $hex);
	$tmp =  base64_encode($pack);
	$uid = preg_replace("#(*UTF8)[^A-Za-z0-9]#", "", $tmp);
	$len = max(4, min(128, $len));
	while (strlen($uid) < $len)
		$uid .= gen_uuid(22);
	return substr($uid, 0, $len);
}

function genRefCode($len = 6)
{
	$code = _generateReferralCode($len);
	while(refCodeExists( $code )) {
		$code = _generateReferralCode($len);
	}
	return $code;
}

function refCodeExists($code)
{
	return db()->query("select id from subscribers where personal_token = '{$code}';")->fetchAll() ? true : false;
}


function confirm($token)
{
    $q = db()->prepare("select `id` from subscribers where `confirm_token` = ? ;");
    $id = ($q->execute(array($token)) and ($res = $q->fetchAll(PDO::FETCH_ASSOC))) ? $res[0]['id'] : false ;
    if ( ! $id) return false;
    $q = db()->prepare("update subscribers set confirmed = 1 where `id` = ? ;");
    return $q->execute(array($id)) ? true : false;
}

function slideshowed($token)
{
    $q = db()->prepare("update subscribers set slideshowed = 1 where `personal_token` = ? ;");
    return $q->execute(array($token)) ? true : false;
}

define('DOMAIN', $_SERVER['SERVER_NAME']);
define('PROTO' , (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']) ? 'https://' : 'http://' );
define('BASEURL', PROTO.DOMAIN.'/');
define('B_SALT','the_w0nd3r');
define('E_SALT','the_h0p');

global $db;
$db = new PDO('mysql:dbname=pre_wonderhop;host=localhost', 'pre_wonderhop', '2oiwnd992');

global $response;
$response = array(
    'redirect' => 0,
);



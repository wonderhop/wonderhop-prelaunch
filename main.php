<?php
include 'Mail.php';
include 'Mail/mime.php' ;

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
function newemail_save($email, $invited_by = NULL, $no_confirm = false)
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
        //if( ! $no_confirm) send_confirm_email($email, $values[2], $inviter['email'], $inviter['personal_token']); 
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

function email_is_invitation($email)
{
    if ( ! $email) return;
    $sub = subscriber($email);
    if ( ! $sub) return;
    db()->query("update subscribers set invitation = 1 where `id` = {$sub['id']};");
}

function invited_friendcount($email)
{
    //return 10;
    $sub = subscriber($email);
    if ( ! $sub) return 0;
    $res = db()->query("select count(*) from subscribers where `invited_by` = {$sub['id']} and confirmed = 1;")->fetchAll();
    //error_log(print_r($res,true));
    return isset($res[0][0]) ? $res[0][0] : 0;
}

function set_ad_code_to_user($user,$ad)
{
    $sub = subscriber($user,'*');
    /* if ( ! $sub or $sub['invitation'] or ! empty($sub['ad_code'])) return; */
    if ( ! $sub or ! empty($sub['ad_code'])) return;
    $q = db()->prepare("update subscribers set `ad_code` = ? where `id` = {$sub['id']};");
    $q->execute(array($ad));
}


function send_confirm_email($email, $token, $inviter_email, $inviter_token)
{
    $body = get_confirmation_email($email, BASEURL . "?c=$token&r=$inviter_token", $inviter_email);
    send_sendgrid_email($email, $body, $body, 'Please confirm your Curio Road account');
}

function send_invitation_email($email, $confirmation_token, $inviter_email, $inviter_token)
{    
    $confirmation_link = personal_link($inviter_email, 1) . '&c=' . $confirmation_token;
    $body = get_email($email, $confirmation_link, $inviter_email);
    $text = "Congrats! $inviter_email just recommended you for membership to Curio Road.\n
            Our members all have a passion for <b>unique and beautiful designs</b> -- and we help them discover one-of-a-kind jewelry, home décor and gifts to inspire their lives. 
            \n
            Membership is free, but spots are limited. <b>Click below to accept your invitation</b> - but hurry, we only have a few spots left this week. \n
            Click here: <a href=\"$confirmation_link\">ACCEPT YOUR INVITATION</a> or copy this $confirmation_link into your browser.
    ";
    //error_log($text);
    //error_log($email);
    //error_log($body);
    send_sendgrid_email($email, $text, $body, "$inviter_email just gave you access to Curio Road");
}

function send_sendgrid_email($email, $text, $html, $subject)
{    
    $crlf = "\n";
    $hdrs = array(
                  'From'    => 'Curio Road <hello@curioroad.com>',
                  'Subject' => $subject,
                  'To'      => $email,
                  );

    $mime = new Mail_mime(array('eol' => $crlf));

    $mime->setTXTBody($text);
    $mime->setHTMLBody($html);

    $body = $mime->get();
    $hdrs = $mime->headers($hdrs);

    $mail =& Mail::factory('smtp', array(
        'host' => 'smtp.sendgrid.net',
        'auth' => true,
        'username' => 'wonderhop',
        'password' => 'd3l16ht'
    ));
    $mail->send($email, $hdrs, $body);
}

function get_confirmation_email($email, $token_url, $inviter_email)
{
    $img_url = BASEURL . 'static/images/newsletter/wonderhop_invite/';
    
    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>You are invited to join Curio Road</title> 

<style type="text/css">
.ReadMsgBody {
width: 100%;}
.ExternalClass {
width: 100%;}    </style>
</head>


<body bgcolor="#fd706b">
<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fd706b">
<tr>
  <td height="50">
  </td>
 </tr>
 <tr>
  <td>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="535" bgcolor="#ffffff">

    <tr>
        <td align="left" valign="top" height="16"><img alt="" border="0" height="16" src="' . $img_url . 'wonderhop_01.png" style="display:block" width="535" /></td>
    </tr>
    
    <tr>
        <td align="center">
            <table cellpadding="0" cellspacing="0" border="0" align="center" width="535" bgcolor="#ffffff">
                <tr>
                    <td align="left" valign="top" width="112"><img alt="" border="0" height="153" src="' . $img_url . 'wonderhop_02.png" style="display:block" width="112" /></td>
                    <td align="center" valign="top" width="323"><a href="http://www.curioroad.com" title="Curio Road" target="_blank"><img alt="" border="0" height="78" src="' . $img_url . 'wonderhop_logo.png" style="display:block" width="321" /></a><br />
                    <font color="#fd706b" size="+1" face="georgia">Thanks for signing up for Curio Road ! You\'re in for a wonderful new shopping experience.</font></td>
                    <td align="left" valign="top" width="100"> </td>
                </tr>
            </table>            
        </td>
    </tr>
    
    <tr>
        <td align="left" valign="middle" height="40"><img alt="" border="0" height="4" src="' . $img_url . 'wonderhop_03.png" style="display:block" width="535" /></td>
    </tr>
    
    <tr>
        <td align="center">
            <table cellpadding="0" cellspacing="0" border="0" align="center" width="535" bgcolor="#ffffff">
                <tr>
                    <td align="left" valign="top" width="70"></td>
                    
                    <td align="center" valign="top" width="395">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="395">
                            <tr>
                                <td width="15" bgcolor="#fff9df"></td>
                                <td align="center" valign="top" width="365" bgcolor="#fff9df"><br /><font color="#7f767e" size="+0" face="georgia"><i>In order to be able to earn cash rewards to spend on our site, please confirm your e-mail by clicking the link below:</i></font><br /><br /></td>
                                <td width="15" bgcolor="#fff9df"></td>
                            </tr>
                            
                            <tr>
                                <td width="15"></td>
                                <td align="center" valign="top" width="365"><br />
                                <a href="'. $token_url .'" title="CONFIRM" target="_blank"><img alt="CONFIRM" border="0" height="40" src="' . $img_url . 'confirm_button.png" style="display:block" width="240" /></a>
                                <br />
                                <a href="https://www.facebook.com/pages/Curio-Road/432587490109791" title="Curio Road on Facebook" target="_blank"><img alt="" border="0" height="29" src="' . $img_url . 'facebook.png" width="31" /></a> &nbsp; <a href="https://twitter.com/curioroad" title="Curio Road on Twitter" target="_blank"><img alt="" border="0" height="29" src="' . $img_url . 'twitter.png" width="32" /></a>
                                <br />
                                <a href="https://www.facebook.com/pages/Curio-Road/432587490109791" title="Curio Road on Facebook" target="_blank" style="color: #7f767e;"><font face="arial" size="-2">FACEBOOK</font></a> &nbsp;&nbsp;  <a href="https://twitter.com/curioroad" title="Curio Road on Twitter" target="_blank" style="color: #7f767e;"><font face="arial" size="-2">TWITTER</font></a> &nbsp;&nbsp;&nbsp;
                                <br /><br /></td>
                                <td width="15"></td>
                            </tr>
                            
                        </table>
                    </td>
                    <td align="right" valign="bottom" width="70"><img alt="" border="0" height="186" src="' . $img_url . 'wonderhop_04.png" style="display:block" width="44" /></td>
                </tr>
            </table>            
        </td>
    </tr>
    
    
    <tr>
        <td align="left" valign="top" height="25"><img alt="" border="0" height="25" src="' . $img_url . 'wonderhop_05.png" style="display:block" width="535" /></td>
    </tr>
    
</table>
</td>
 </tr>
 <tr>
  <td height="50">
  </td>
 </tr>
 </table>
<br />


</body>
</html> ';
    return $body;
}

function get_email($email, $token_url, $inviter_email)
{
    $img_url = BASEURL . 'static/images/newsletter/wonderhop_invite/';
    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>You are invited to join Curio Road</title> 

<style type="text/css">
.ReadMsgBody {
width: 100%;}
.ExternalClass {
width: 100%;}    </style>
</head>


<body bgcolor="#fd706b">
<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fd706b">
<tr>
  <td height="50">
  </td>
 </tr>
 <tr>
  <td>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="535" bgcolor="#ffffff">

    <tr>
        <td align="left" valign="top" height="16"><img alt="" border="0" height="16" src="' . $img_url . 'wonderhop_01.png" style="display:block" width="535" /></td>
    </tr>
    
    <tr>
        <td align="center">
            <table cellpadding="0" cellspacing="0" border="0" align="center" width="535" bgcolor="#ffffff">
                <tr>
                    <td align="left" valign="top" width="112"><img alt="" border="0" height="153" src="' . $img_url . 'wonderhop_02.png" style="display:block" width="112" /></td>
                    <td align="center" valign="top" width="323"><a href="http://www.curioroad.com" title="Curio Road" target="_blank"><img alt="" border="0" height="78" src="' . $img_url . 'wonderhop_logo.png" style="display:block" width="321" /></a><br />
                    <font color="#fd706b" size="+1" face="georgia">Congrats! <i>' . $inviter_email . '</i> just recommended you for membership to Curio Road.</font></td>
                    <td align="left" valign="top" width="100"> </td>
                </tr>
            </table>            
        </td>
    </tr>
    
    <tr>
        <td align="left" valign="middle" height="40"><img alt="" border="0" height="4" src="' . $img_url . 'wonderhop_03.png" style="display:block" width="535" /></td>
    </tr>
    
    <tr>
        <td align="center">
            <table cellpadding="0" cellspacing="0" border="0" align="center" width="535" bgcolor="#ffffff">
                <tr>
                    <td align="left" valign="top" width="70"></td>
                    
                    <td align="center" valign="top" width="395">
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="395">
                            <tr>
                                <td width="15" bgcolor="#fff9df"></td>
                                <td align="center" valign="top" width="365" bgcolor="#fff9df">
                                    <br />
                                    <font color="#7f767e" size="+0" face="georgia">
                                        <i>
                                            Our members all have a passion for <b>unique and beautiful designs</b> &mdash; and we help them discover one-of-a-kind jewelry, home decor and gifts to inspire their lives. 
                                            <br /><br />
                                            Membership is free, but spots are limited.<br/> <b>Click below to accept your invitation</b> - <br/> but hurry, we only have a few spots left this week.
                                        </i>
                                    </font>
                                    <br />
                                    <br />
                                </td>
                                <td width="15" bgcolor="#fff9df"></td>
                            </tr>
                            
                            <tr>
                                <td width="15"></td>
                                <td align="center" valign="top" width="365"><br />
                                <a href="'. $token_url .'" title="Accept your invitation" target="_blank" style="background:#A994A4;color: white; display: block; font-family: Arial,Helvetica,sans-serif; font-size: 14px; font-weight: bold; height: 40px; letter-spacing: 0.5px; text-decoration: none; text-transform: uppercase; vertical-align: middle; width: 240px; position:relative;">
                                    <img alt="" border="0" src="' . $img_url . 'accept_top_12.png" width="240" height="12" style="display:block; position:absolute; top:10px; left:0;"/>
                                    <img alt="Accept Your Invitation" border="0" src="' . $img_url . 'accept_bottom_28.png" width="240" height="28" style="display:block; position:absolute; top:10px; left:0;"/>
                                </a>
                                <br />
                                <br />
                                <a href="https://www.facebook.com/pages/Curio-Road/432587490109791" title="Curio Road on Facebook" target="_blank"><img alt="" border="0" height="29" src="' . $img_url . 'facebook.png" width="31" /></a> &nbsp; <a href="https://twitter.com/curioroad" title="Curio Road on Twitter" target="_blank"><img alt="" border="0" height="29" src="' . $img_url . 'twitter.png" width="32" /></a>
                                <br />
                                <a href="https://www.facebook.com/pages/Curio-Road/432587490109791" title="Curio Road on Facebook" target="_blank" style="color: #7f767e;"><font face="arial" size="-2">FACEBOOK</font></a> &nbsp;&nbsp;  <a href="https://twitter.com/curioroad" title="Curio Road on Twitter" target="_blank" style="color: #7f767e;"><font face="arial" size="-2">TWITTER</font></a> &nbsp;&nbsp;&nbsp;
                                <br /><br /></td>
                                <td width="15"></td>
                            </tr>
                            
                        </table>
                    </td>
                    <td align="right" valign="bottom" width="70"><img alt="" border="0" height="186" src="' . $img_url . 'wonderhop_04.png" style="display:block" width="44" /></td>
                </tr>
            </table>            
        </td>
    </tr>
    
    
    <tr>
        <td align="left" valign="top" height="25"><img alt="" border="0" height="25" src="' . $img_url . 'wonderhop_05.png" style="display:block" width="535" /></td>
    </tr>
    
</table>
</td>
 </tr>
 <tr>
  <td height="50">
  </td>
 </tr>
 </table>
<br />


</body>
</html> ';
    return $body;
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



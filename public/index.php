<?php

// stuff
require_once dirname(__FILE__) . '/../main.php';


if (isset($_GET['c']) and preg_match('/^[0-9a-f]+$/i', $_GET['c'])) {
    confirm($_GET['c']);
}
$set_ad_code = true;
if (isset($_COOKIE['prewh_email']) and isset($_GET['a']) and ($ad_sub = subscriber($_COOKIE['prewh_email'])) and empty($ad_sub['ad_code'])) {
    set_ad_code_to_user($_COOKIE['prewh_email'], $_GET['a']);
    $set_ad_code = false;
}

?><!DOCTYPE html>
<html>
<head>
    <title>Curio Road</title>
    <meta http-equiv="Content-Type" name="content-type" content="text/html; charset=UTF-8" />
    <meta name="title" content="Curio Road | Unique finds for an inspired life" />
    <meta name="description" content="Discover uniquely lovely jewelry, home decor and gifts, all at insider prices. Get inspired today!" />
    <meta property="og:description" content="Discover uniquely lovely jewelry, home decor and gifts, all at insider prices. Get inspired today!" />
    <meta property="og:title"  name="title" content="Grab a $5 credit to shop Curio Road." />
    
    <?php
    if(file_exists($tk_file = (dirname(__FILE__) . '/../tk_domain.key')) 
      and ($tk_key = trim(file_get_contents($tk_file)))) {
        ?>
        <script type="text/javascript" src="//use.typekit.net/<?php echo $tk_key; ?>.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
        <?php
    }
    ?>
    
    <link rel="image_src" type="image/jpeg" href="/static/images/fb_thumb.jpeg" />
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Cantata+One">
    <link rel="stylesheet" type="text/css" href="static/css/style.css"/>
    <style>
        
    </style>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="static/js/jquery.backstretch.min.js"></script>
    <!--<script type="text/javascript" src="static/js/stellar.js"></script>-->
    <!--<script type="text/javascript" src="static/js/jquery.scrolling-parallax.js"></script>-->
    <script type="text/javascript" src="static/js/jquery.inview.min.js"></script>
    <script type="text/javascript" src="static/js/jquery.scrollTo.js"></script>
    <!--<script type="text/javascript" src="static/js/jquery.parallax.js"></script>-->
    <script type="text/javascript" src="static/js/script.js"></script>
    <script type="text/javascript">
        var Subscriber = null;
        
        function getParameterByName(name)
        {
            var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
            return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
        }
        
        function fadeText()
        {
            
        }
        
        function getURef()
        {
            return window.location.href.indexOf('?r=') > -1 ? getParameterByName('r') : null;
        }
        
        function getCRef()
        {
            return window.location.href.indexOf('c=') > -1 ? getParameterByName('c') : null;
        }
        
        function getARef()
        {
            return window.location.href.indexOf('a=') > -1 ? getParameterByName('a') : null
        }
        
        /*
        function getConfirm()
        {
            return window.location.href.indexOf('?c=') > -1 ? getParameterByName('c') : false;
        }
        
        var isConfirm = getConfirm() ? true : false;
        
        if (isConfirm) { (function($){$(function(){
            var confirms = getConfirm();
            
                
        });})(jQuery); }
        */
        function slideshowing(Sub)
        {
            if ( ! Sub.slideshowed) {
                mixpanel.track('slide 1');
                (function($){
                    $('.ctext.ctext-11').bind('inview', function(ev,is_in){
                        var $slide = $(this);
                        if ($slide.data('slideshowed')) return;
                        else {
                            if(is_in) {
                                $slide.data('slideshowed', true);
                                Sub.slideshowed = true;
                                $.post('/slideshowed.php','code='+Sub.personal_token);
                            }
                        }
                    });
                })(jQuery);
            }
        }
        
        function setCookie (c_name, value, exp, as_seconds)
        {
            var exdate = new Date( (new Date().getTime()) + (exp * (as_seconds ? 1000 : (1000 * 60 * 60 * 24))) );
            var c_value=escape(value) + ((exp==null) ? "" : "; expires="+exdate.toUTCString());
            document.cookie=c_name + "=" + c_value+"; path=/";
        }
        
        
        function getCookie (c_name)
        {
            var i, x, y, ARRcookies = document.cookie.split(";");
            for (i=0; i<ARRcookies.length; i++) {
                x = ARRcookies[i].substr( 0, ARRcookies[i].indexOf("=") );
                y = ARRcookies[i].substr( ARRcookies[i].indexOf("=") + 1 );
                x = x.replace( /^\s+|\s+$/g , "" );
                if ( x == c_name ) {
                    return unescape(y);
                }
            }
        }
         
    </script>
    <!-- start Mixpanel --><script type="text/javascript">(function(c,a){window.mixpanel=a;var b,d,h,e;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src=("https:"===c.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.1.min.js';d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d);a._i=[];a.init=function(b,c,f){function d(a,b){var c=b.split(".");2==c.length&&(a=a[c[0]],b=c[1]);a[b]=function(){a.push([b].concat(Array.prototype.slice.call(arguments,0)))}}var g=a;"undefined"!==typeof f?
g=a[f]=[]:f="mixpanel";g.people=g.people||[];h="disable track track_pageview track_links track_forms register register_once unregister identify name_tag set_config people.identify people.set people.increment".split(" ");for(e=0;e<h.length;e++)d(g,h[e]);a._i.push([b,c,f])};a.__SV=1.1})(document,window.mixpanel||[]);
mixpanel.init("<?php $key_file = dirname(__FILE__) . '/../mp_domain.key';
                     echo file_exists($key_file) 
                            ? trim(file_get_contents($key_file)) 
                            : '0cd44088582225fed6fb19d04638a752'; // default to test account
                            ?>");</script><!-- end Mixpanel -->
</head>
<body>
<div id="email_screen" <?php if (isset($_COOKIE['prewh_email'])): ?> style="display:none" <?php endif; ?>>
    
    <?php if (!isset($_COOKIE['prewh_email'])): ?> 
        <script type="text/javascript">
            mixpanel.track('homepage');
           jQuery.backstretch('/static/images/overlay1.jpg');
        </script>
     <?php endif; ?>
    <!--<div class="email_box">-->
        <div class="popup_register block_popup" >
            <div class="block_popup_inner" style="background:white;">
                <div class="block_inner_content" style="width:100%;height:100%;">
                    <h1 class="popup_title">Wonderful finds <br/>for an inspired life</h1>
                    <div class="control_link">
                        <a href="#" onclick="changePopupContentLogin();" id="login_link">LOGIN</a>
                        <a href="#" onclick="is_referred ? changePopupContentReferred() : changePopupContentRegister();return false;" id="back_link" style="display:none;">BACK</a>
                    </div>
                    <script type="text/javascript">
                        function changePopupContentLogin(){
                            var $ = jQuery,
                                title = 'Welcome back! <br/><br/>',
                                text_1 = 'Log in to invite friends to Curio Road and',
                                text_2 = 'earn cash to spend on our site when we launch.',
                                btn_text = 'LOGIN NOW';
                            $('.block_inner_content').fadeOut(200,function(ev){
                                $('.popup_title').html(title);
                                $('.popup_content p.pc_l1').html(text_1);
                                $('.popup_content p.pc_l2').html(text_2);
                                $('#login_link').hide();
                                $('#back_link').show();
                                $('.actions button span').html(btn_text);
                                $('.block_inner_content').fadeIn(200);
                             });
                        }
                        function changePopupContentRegister(){
                            var $ = jQuery,
                                title = 'Wonderful finds<br/>for an inspired life',
                                text_1 = 'Uniquely lovely jewelry, home decor',
                                text_2 = 'and gifts. All at insider prices.',
                                btn_text = 'Become a member';
                            $('.block_inner_content').fadeOut(200, function() {
                                $('.popup_title').html(title);
                                $('.popup_content p.pc_l1').html(text_1);
                                $('.popup_content p.pc_l2').html(text_2);
                                $('#login_link').show();
                                $('#back_link').hide();
                                $('.actions button span').html(btn_text);
                                $('.block_inner_content').fadeIn(200);
                            });
                        }
                        
                        function changePopupContentReferred(){
                            var $ = jQuery,
                                title = 'Wonderful finds<br/>for an inspired life',
                                text_1 = 'Claim your $5 gift here. Uniquely lovely',
                                text_2 = 'jewelry, home decor, and gifts.',
                                btn_text = 'Claim Your Gift';
                            $('.block_inner_content').fadeOut(200, function() {
                                $('.popup_title').html(title);
                                $('.popup_content p.pc_l1').html(text_1);
                                $('.popup_content p.pc_l2').html(text_2);
                                $('#login_link').show();
                                $('#back_link').hide();
                                $('.actions button span').html(btn_text);
                                $('.block_inner_content').fadeIn(200);
                            });
                        }
                    </script>
                    <div class="popup_content">
                        <p class="pc_l pc_l1">Uniquely lovely jewelry, home decor</p> 
                        <p class="pc_l pc_l2">and gifts. All at insider prices.</p>
                        <form onSubmit="return false;" action="" method="post" id="register-form1">
                            <input type="hidden" value="" name="url" class="document_url"/>
                            <div class="content">
                                <ul class="form-list">
                                    <li>
                                        <input name="email" title="Your Email Address'" id="email" type="email" class="input-text required-entry validate-duplicate validate-email" />
                                        <script type="text/javascript">(function($){$(function(){
                                            var popup_email = '.popup_register .form-list input#email', $e = $(popup_email),
                                                pt = 'Your email address';
                                            if ( ! $e || ! $e.length)  return;
                                            ( ! $e.val() || ! $e.val().length) && $e.val(pt);
                                            $e.focus(function(){ $e.val() == pt && $e.val(''); })
                                                .blur(function(){ ( ! $e.val() || ! $e.val().length) && $e.val(pt); });
                                        });})(jQuery);</script>
                                    </li>    
                                </ul>       
                            </div>
                            <div class="actions">
                                <button class="button" type="submit" name="register" id="become_member">
                                    <span>Become a member</span>
                                </button>
                            </div>
                        </form> 
                        <script type="text/javascript">
                            if ( ! getCRef() && getURef()) {
                                console.log('is_referred');
                                is_referred = true;
                                changePopupContentReferred();
                            }
                        </script>
                    </div>
                
                <script type="text/javascript">
                    (function($){
                        var $regForm = $('#register-form1');
                        $regForm.submit(function(ev) {
                            ev.preventDefault();
                            $('wrap').show();
                            var URef = getURef();
                            console.log(URef);
                            if (URef) {
                                $regForm.append('<input type="hidden" name="inviter" value="'+URef+'" />');
                            }
                            $.post('/save.php', $regForm.serialize(), function(data){
                                resp = null;
                                try{
                                    resp = JSON.parse(data);
                                }catch(e) {
                                }
                                if (resp) {
                                    if(resp.success) {
                                        // do success
                                        Subscriber = resp.subscriber;
                                        Subscriber.confirmed = parseInt(Subscriber.confirmed) ? true : false;
                                        Subscriber.slideshowed = parseInt(Subscriber.slideshowed) ? true : false;
                                        Subscriber.personal_link = resp.personal_link;
                                        if ( ! getCookie('prewh_email')) {
                                            setCookie('prewh_email',Subscriber.email,15);
                                        }
                                        //if ( ! Subscriber.confirmed) {
                                        //    console.log('gigi');
                                        //    $('.popup_title').html('Confirmation needed !<br/>');
                                        //    $('.popup_content').css({height:78,overflow:'visible'})
                                        //    .html('Please check your email for a confirmation link we sent you, follow it and come back <br/><br/> Thanks,');
                                        //    return;
                                        //}
                                        $('#backstretch').clone().prop('id','bsclone').prependTo('#email_screen').css('position','absolute');
                                        $('#backstretch').hide().remove();
                                        var h = $('#email_screen').height(), css = {top:-h};
                                        var $html = $('html');
                                        var htmlcss = {
                                            position: $html.css('position'), 
                                            width:'auto', 
                                            height:'auto',
                                            overflow:$html.css('overflow')
                                        }
                                        //$('html').css({position:'fixed', width:'100%', height:'100%', overflow:'hidden'});
                                        //$('#wrap').fadeIn('fast').delay(300);
                                        $('#wrap').show();
                                        if (resp.existing && Subscriber.slideshowed) {
                                            mixpanel.track('last slide');
                                            mixpanel.people.set({"$email": Subscriber.email, "$id" :Subscriber.personal_token});
                                            mixpanel.people.identify(Subscriber.personal_token);
                                            mixpanel.name_tag(Subscriber.email);
                                            
                                            $.scrollTo(9300);
                                            if (getCookie('prewh_email')) {
                                               $('#wrap').css('visibility', 'visible');
                                            }
                                        } else {
                                            slideshowing(Subscriber);
                                        }
                                        $('#email_screen').animate(css, {
                                            duration: 800 ,
                                            easing: 'swing',
                                            complete: function(){
                                                $('#email_screen').delay(300).hide().remove();
                                                //$html.css(htmlcss);
                                                
                                                $('#personal-link').val(resp.personal_link);
                                                
                                                if (getARef() && <?php echo $set_ad_code ? 'true' : 'false'; ?>) {
                                                    $.get('/adcode.php','a=' + getARef() + '&e=' + encodeURIComponent(Subscriber.email), function(data){
                                                       console.log('jsa');
                                                    });
                                                }
                                                $.post('/friendcount.php','count=1',function(data){
                                                    try{
                                                        resp = JSON.parse(data);
                                                    }catch(e){
                                                    }
                                                    if (resp && parseInt(resp.invited)) {
                                                        friends_invited(resp.invited);
                                                    }
                                                });
                                            },
                                            queue:false,
                                        });
                                        
                                        //$('#email_screen, #backstretch').delay(300).fadeOut(800);
                                        
                                    } else {
                                        input_error($('#email',$regForm), 'Email address invalid or server error !');
                                        if (resp.redirect) {
                                            window.location.href = resp.redirect;
                                        }
                                        return false;
                                    }
                                }
                                
                            });
                        });
                        
                        
                        $(document).ready(function(){
                           
                            if (getCookie('prewh_email')) {
                                    $('#email').val(getCookie('prewh_email'));
                                    $regForm.submit();
                                    
                            }
                        });
                        
                    })(jQuery)
                    
                    function input_error()
                    {
                        alert('error');
                    }
                    
                    
                </script>
                
                </div><!-- block_popup_inner_content -->
            </div>
        </div>
    <!--</div>-->
    
</div>

<div id="wrap" <?php if (isset($_COOKIE['prewh_email']) and ($csub = subscriber($_COOKIE['prewh_email'])) and $csub['slideshowed']): ?> style="display:block; visibility: hidden;" <?php endif; ?>>
    
    <div id="content-back" data-stellar-background-ratio="0.7">
        <div class="backslide backslide-1"><div class="back cblue bcdarkpurple"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-2"><div class="back cpink bcdarkbrown"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-3"><div class="back cgreen bcdarkgreen"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-4"><div class="back cblue bcdarkblue"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-5"><div class="back cpurple bcwhatever"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-6"><div class="back cpink bcbrown"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-7"><div class="back cyellow bcanothergreen"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-8"><div class="back cblue bcindigo"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-9"><div class="back cpink bcheavyorange"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-10"><div class="back corange bcheavyorange2"></div><div class="backwhite"></div></div>
        <div class="backslide backslide-11"><div class="back cred"></div><div class="backwhite"></div></div>
    </div>
    
    <div class="backwhitefix"></div>
    
    
    <div id="content-box">
        <div id="content-cards" class="cnt">
            <div class="card card-1 card_1"></div>
            <div class="card card-2 card_2"></div>
            <div class="card card-3 card_3"></div>
            <div class="card card-4 card_4"></div>
            <div class="card card-5 card_1"></div>
            <div class="card card-6 card_4"></div>
            <div class="card card-7 card_1"></div>
            <div class="card card-8 card_3"></div>
            <div class="card card-9 card_1"></div>
            <div class="card card-10 card_4"></div>
            <div class="card card-11 card_page"></div>
        </div>
        <div id="content-text" class="cnt">
            <div class="ctext ctext-1"><div class="ctext-back-1"></div><img class="ctext-img-1" src="/static/images/texts/text_1.png" /></div>
            <div class="ctext ctext-2"><div class="ctext-back-2"></div><img class="ctext-img-2" src="/static/images/texts/text_2.png" /></div>
            <div class="ctext ctext-3"><div class="prod_img prod_img-3"><img class="prod-img-3" src="/static/images/prod_img/3/prod_img_3.jpg" /></div><div class="ctext-back-3"></div><img class="ctext-img-3" src="/static/images/texts/text_3.png" /></div>
            <div class="ctext ctext-4"><div class="prod_img prod_img-4"><img class="prod-img-4" src="/static/images/prod_img/3/prod_img_4.jpg" /></div><div class="ctext-back-4"></div><img class="ctext-img-4" src="/static/images/texts/text_4.png" /></div>
            <div class="ctext ctext-5"><div class="prod_img prod_img-5"><img class="prod-img-5" src="/static/images/prod_img/3/prod_img_5.jpg" /></div><div class="ctext-back-5"></div><img class="ctext-img-5" src="/static/images/texts/text_5.png" /></div>
            <div class="ctext ctext-6"><div class="prod_img prod_img-6"><img class="prod-img-6" src="/static/images/prod_img/3/prod_img_6.jpg" /></div><div class="ctext-back-6"></div><img class="ctext-img-6" src="/static/images/texts/text_6.png" /></div>
            <div class="ctext ctext-7"><div class="prod_img prod_img-7"><img class="prod-img-7" src="/static/images/prod_img/3/prod_img_7.jpg" /></div><div class="ctext-back-7"></div><img class="ctext-img-7" src="/static/images/texts/text_7.png" /></div>
            <div class="ctext ctext-8"><div class="prod_img prod_img-8"><img class="prod-img-8" src="/static/images/prod_img/3/prod_img_8.jpg" /></div><div class="ctext-back-8"></div><img class="ctext-img-8" src="/static/images/texts/text_8.png" /></div>
            <div class="ctext ctext-9"><div class="prod_img prod_img-9"><img class="prod-img-9" src="/static/images/prod_img/3/prod_img_9.jpg" /></div><div class="ctext-back-9"></div><img class="ctext-img-9" src="/static/images/texts/text_9.png" /></div>
            <div class="ctext ctext-10"><div class="ctext-back-10"></div><img class="ctext-img-10" src="/static/images/texts/text_10.png" /></div>
            
            <!-- inite page -->
            <!-- Include these scripts to import address books with CloudSponge -->
            <script type="text/javascript" src="https://api.cloudsponge.com/address_books.js"></script>
            <script type="text/javascript" charset="utf-8">
                var csPageOptions = {
                    domain_key:"<?php
                                    $key_file = dirname(__FILE__) . '/../cs_domain.key';
                                    echo file_exists($key_file) ? trim(file_get_contents($key_file)) : ''; ?>",
                    textarea_id:"cloud_invite_input"
                };
                
                (function($){$(function(){
                    $('#cloud_invite_input, .csicon, .elements_container').click(function(ev){
                        console.log('clk');
                        ev.stopPropagation();
                        //ev.preventDefault();
                        //$(this).focus();
                    });
                    $('.backwhitefix, .ctext').not('.ctext-11').click(function(){
                        var page = parseInt(window.scrollY /920) + 1;
                        console.log(page);
                        if (page > 10) return;
                        var ph = 920, diffr = 0;
                        //if (page === 1) diffr = 100;
                        //if (page === 2) diffr = 50;
                        var offset = ((page * 920) - diffr);
                        //console.log(page);
                        //console.log(offset);
                        $(window).stop();
                        $.scrollTo(offset, 1400);
                    });
                    $('#invitations-form').submit(function(ev,data){
                        $form = $(this);
                        if ( ! $('#cloud_invite_input').val()) return false;
                        $.post('/invite.php',$form.serialize(),function(data){
                            // alert('friend invited');
                            resp = null;
                            try {
                                resp = JSON.parse(data);
                            } catch(e) {
                            }
                            if (resp) {
                                if (resp.redirect) {
                                    window.location.href = resp.redirect;
                                    return false;
                                }
                                mixpanel.track('Invitation send');
                                $('#invitations-sent').fadeIn('fast').delay(1000).fadeOut('slow');
                                $('#cloud_invite_input').val('');
                            }
                        });
                        ev.preventDefault();
                        return false;
                    });
                    
                    
                    
                });})(jQuery);
            </script>
                
            <!-- Any link with a class="cs_import" will start the import process -->
            <!-- <a class="cs_import">Add from Address Book</a> -->
                
            <!-- This textarea will be populated with the contacts returned by CloudSponge -->
            <!-- <textarea id="contact_list" style="width:450px;height:82px"></textarea> -->
            
            
            <div class="ctext ctext-11">
                <div class="ctext-back-11"></div>
                <!--<img class="ctext-img-11" src="/static/images/invite_page-nq8.png" />-->
                <img class="ctext-img-11" src="/static/images/texts/text_11_1.png" />
                <div class="elements_container" style="position:absolute;overflow:hidden;width:100%;height:100%;">
                    
                    <div id="popup-overlay" style="display:none"></div>
                    <div id="popup-content" style="display:none">
                        <div id="close_popup">X</div>                     
                        <div id="import_loader" style="display: none">
                            <p>Your Contacts Are Loading</p>
                            <img src="<?php // echo $this->getSkinUrl('images/opc-ajax-loader.gif'); ?>" />
                        </div>
                        <div class="email_addresses" id="ul_email_add" style="display:none;">
                            <ul class="form-list address_records_block address_records" id="address_records" style="display:none;"></ul>
                        </div>
                        <div class="buttons-set-invite">
                            <button type="button"  style="display:none;" title="Proceed to Checkout" id="select_emails" class="button cloudsponge_select_emails"><span><span>Select</span></span></button>
                        </div>
                    </div>
                    
                    <form action="/invite.php" method="post" id="invitations-form" style=" ">
                        <div id="invite-links-container">
                            <a id="cloudsponge_gmail" onclick="cloudsponge.launch('gmail');return false;" href="#" class="csicon cloudsponge_gmail invite-import-gmail">Gmail</a>  
                            <a id="cloudsponge_yahoo" onclick="cloudsponge.launch('yahoo');return false;" href="#" class="csicon cloudsponge_yahoo invite-import-yahoo">Yahoo</a>
                            <a id="cloudsponge_msn" onclick="cloudsponge.launch('msn');return false;" href="#" class="csicon cloudsponge_msn invite-import-hotmail">Hotmail</a>
                            <a onclick="return cloudsponge.launch('aol');return false;" href="#" class="csicon invite-import-aol">Aol</a> 
                        </div>

                        <input type="hidden" id="key" value="" />
                        <input id="cloud_invite_input" class="invite-emails input-text required-entry" size="150" rel="1" type="text" name="emails" value="" />
                        <button id="cloud_invite_submit" type="submit" title="Send Invite" class="button"><span><span>Send</span></span></button>
                        <div id="invitations-sent" style="font-size:14px;color:#FF7268;position:absolute;right:-60px;bottom:55px;display:none;">INVITATIONS SENT !</div>
                    </form>
                    
                    <div id="gifts" style="">
                        <div class="money initial">
                            <span>GET STARTED!</span>
                        </div>
                        <ul class="indicator">
                            <li class="i-level i-level-6"><span class="ammount">$60</span><span class="remainder"></span></li>
                            <li class="i-level i-level-5"><span class="ammount">$50</span><span class="remainder"></span></li>
                            <li class="i-level i-level-4"><span class="ammount">$40</span><span class="remainder"></span></li>
                            <li class="i-level i-level-3"><span class="ammount">$20</span><span class="remainder"></span></li>
                            <li class="i-level i-level-2"><span class="ammount">$10</span><span class="remainder"></span></li>
                            <li class="i-level i-level-1"><span class="ammount">SKIP THE LINE,<br/>SHOP ON 10/10</span><span class="remainder"></span></li>
                        </ul>
                    </div>
                    <script type="text/javascript">(function($) {
                        
                        function friends_invited(fcount)
                        {
                            //var fc = (fcount > 30) ? 30 : fcount, 
                            //    rem = (fc%5) ? (5 - (fc%5)) : 0,
                            //    level = parseInt((fc - (rem ? 5 - rem : 0)) /5);
                            ////if (fcount < 5) {
                            ////    fc = fcount; level = 0; rem = 5-fcount;
                            ////}
                            //console.log(fc, rem, level);
                            //activate_winning(level, rem);
                            var fc = (fcount > 15) ? 15 : fcount, rem, level, msg = 0;
                            switch(true) {
                                case (fc <= 2) :
                                    level = 1; rem = 2-fc; msg= 'skip the line<br/>shop on 10/10'; break;
                                case (fc <= 6) :
                                    level = 2; rem = 6-fc; break;
                                case (fc <= 10):
                                    level = 3; rem = 10-fc; break;
                                case (fc <= 15):
                                    level = 4; rem = 15-fc; break;
                                default:
                                    level = 0; rem = 0;
                            }
                            activate_winning(level,rem, msg);
                        }
                        
                        function activate_winning(level, remainder, msg, add_msg_class, rem_msg_class)
                        {
                            var $cc = $('.i-level-'+level);
                            if ( ! $cc.length && level != 0) return;
                            show_level(level);
                            $('.indicator li').removeClass('current').removeClass('remaining');
                            $('.money.initial').removeClass('initial');
                            if (remainder && remainder != 0) {
                                var $nc = $('.i-level-'+(level+1));
                                if ( ! $nc.length) return;
                                $('.indicator li').removeClass('remains');
                                $('.remainder',$nc).html(''+remainder+'<br/><span style="color:gray;font-weight:normal;">more</span>');
                                $nc.addClass('remaining');
                                $('.money span').html('+' + remainder + ' INVITES TO ' + $('.ammount',$nc).text() + '!');
                            } else {
                                $cc.addClass('current');
                                msg = msg ? msg : ('YOU\'VE EARNED ' + $('.ammount',$cc).html() + '!');
                                $('.money span').html(msg);
                                if (add_msg_class) $('.money span').addClass(add_msg_class);
                                if (rem_msg_class) $('.money span').removeClass(rem_msg_class);
                            }
                        }
                        
                        function show_level(level)
                        {
                            $('.indicator li').removeClass('active').removeClass('current');
                            for(var i=1; i<=level; i++) {
                                var $c = $('.i-level-'+i);
                                if ($c.length) {
                                    $c.addClass('active');
                                    if (i == level) $c.addClass('current')
                                }
                            }
                        }
                        
                        window.activate_winning = activate_winning;
                        window.friends_invited = friends_invited;
                        window.show_level = show_level;
                        
                        <?php if (isset($_COOKIE['prewh_email']) and $fcount = invited_friendcount($_COOKIE['prewh_email'])) { ?>
                            $(function(){ friends_invited( <?php echo $fcount; ?> ); });
                        <?php } ?>
                        
                    })(jQuery);</script>
                    
                    
                    <input type="text" id="personal-link" value="" onfocus="setTimeout(function(){jQuery('#personal-link').select();}, 100);"/>
                    
                    <div id="sharebox">

                        <div class="fb-share shareitem" onclick="post_on_wall();">
                            <a href="#" name="fb_share" type="icon_link" share_url=""></a>
                        </div>
                        <script type="text/javascript">
                            function post_on_wall() {
                                var personal_link = Subscriber.personal_link;
                                jQuery('.fb-share.shareitem a').attr('share_url',personal_link);
                                url = 'http://www.facebook.com/sharer.php?u='+personal_link+'&t=Grab a $5 credit to shop Curio Road.';
                                window.open(url, "Post on Wall", "width=780, height=410, toolbar=0, scrollbars=0, status=0, resizable=0, location=0, menuBar=0, left=0, top=0");
                            }
                        </script>

                        <div class="twitter-share shareitem" onclick="tweet_it();">
                            <a href="#" title="Share this on Twitter" id="service-links-twitter" class="service-links-twitter" rel="nofollow" target="_blank"></a>
                            <script type="text/javascript">
                                function tweet_it() {
                                    var width  = 575,
                                    height = 400,
                                    left   = (jQuery(window).width()  - width)  / 2,
                                    top    = (jQuery(window).height() - height) / 2,
                                    url    = 'http://twitter.com/share/?url=' + Subscriber.personal_link,
                                    eurl   = url+'&text=Grab a $5 credit to shop Curio Road. Discover uniquely lovely jewelry, home decor and gifts, all at insider prices',
                                    opts   = 'status=1' +
                                            ',width='  + width  +
                                            ',height=' + height +
                                            ',top='    + top    +
                                            ',left='   + left;
                                    jQuery('.twitter-share.shareitem a').attr('href',Subscriber.personal_link);
                                    window.open(eurl, 'Twitter', opts);
                                    return false;
                                }
                            </script>
                        </div>
                    </div> <!-- #sharebox -->
                    
                <div><!-- .elements_container -->
            </div><!-- .ctext-11 -->
            
            
        </div><!-- #content-text -->
    </div><!-- #content-box -->
    
</div><!-- wrap -->

</body>
</html>

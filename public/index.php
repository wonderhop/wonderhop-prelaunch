<?php

// stuff
require_once dirname(__FILE__) . '/../main.php';


if (isset($_GET['c']) and preg_match('/^[0-9a-f]+$/i', $_GET['c'])) {
    confirm($_GET['c']);
}

?><!DOCTYPE html>
<html>
<head>
    <title>Wondrohp opens soon !</title>
    <meta http-equiv="Content-Type" name="content-type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Cantata+One">
    <link rel="stylesheet" type="text/css" href="static/css/style.css"/>
    <style>
        
    </style>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="static/js/jquery.backstretch.min.js"></script>
    <script type="text/javascript" src="static/js/stellar.js"></script>
    <script type="text/javascript" src="static/js/jquery.scrolling-parallax.js"></script>
    <script type="text/javascript" src="static/js/jquery.inview.min.js"></script>
    <script type="text/javascript" src="static/js/jquery.scrollTo.js"></script>
    <script type="text/javascript" src="static/js/jquery.parallax.js"></script>
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
            return window.location.href.indexOf('?r=') > -1 ? getParameterByName('r') : window.location.href.split('/').pop();
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
</head>
<body>
<div id="email_screen" <?php if (isset($_COOKIE['prewh_email'])): ?> style="display:none" <?php endif; ?>>
    <?php if (!isset($_COOKIE['prewh_email'])): ?> 
        <script type="text/javascript">
           jQuery.backstretch('/static/images/overlay.jpg');
        </script>
     <?php endif; ?>
    <!--<div class="email_box">-->
        <div class="popup_register block_popup" >
            <div class="block_popup_inner" style="background:white;">
                <div class="block_inner_content" style="width:100%;height:100%;">
                    <h1 class="popup_title">Wonderful finds <br/>for an inspired life</h1>
                    <div class="control_link">
                        <a href="#" onclick="changePopupContentLogin();" id="login_link">LOGIN</a>
                        <a href="#" onclick="changePopupContentRegister();" id="back_link" style="display:none;">BACK</a>
                    </div>
                    <script type="text/javascript">
                        function changePopupContentLogin(){
                            var $ = jQuery,
                                title = 'Good to <br/>have you back<br/>',
                                text_1 = 'Login with your email address below to',
                                text_2 = 'see your gift cards.',
                                btn_text = 'Login';
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
                                text_1 = 'Uniquely lovely finds to inspire your',
                                text_2 = 'home, kitchen and family.',
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
                    </script>
                    <div class="popup_content">
                        <p class="pc_l pc_l1">Uniquely lovely finds to inspire your</p> 
                        <p class="pc_l pc_l2">home, kitchen and family.</p>
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
											$.scrollTo(9300);
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

<div id="wrap" <?php if (isset($_COOKIE['prewh_email'])): ?> style="display:block" <?php endif; ?>>
    
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
            <div class="ctext ctext-3"><div class="prod_img prod_img-3"><img class="prod-img-3" src="/static/images/prod_img/1/prod_img_3.jpg" /></div><div class="ctext-back-3"></div><img class="ctext-img-3" src="/static/images/texts/text_3.png" /></div>
            <div class="ctext ctext-4"><div class="prod_img prod_img-4"><img class="prod-img-4" src="/static/images/prod_img/1/prod_img_4.jpg" /></div><div class="ctext-back-4"></div><img class="ctext-img-4" src="/static/images/texts/text_4.png" /></div>
            <div class="ctext ctext-5"><div class="prod_img prod_img-5"><img class="prod-img-5" src="/static/images/prod_img/1/prod_img_5.jpg" /></div><div class="ctext-back-5"></div><img class="ctext-img-5" src="/static/images/texts/text_5.png" /></div>
            <div class="ctext ctext-6"><div class="prod_img prod_img-6"><img class="prod-img-6" src="/static/images/prod_img/1/prod_img_6.jpg" /></div><div class="ctext-back-6"></div><img class="ctext-img-6" src="/static/images/texts/text_6.png" /></div>
            <div class="ctext ctext-7"><div class="prod_img prod_img-7"><img class="prod-img-7" src="/static/images/prod_img/1/prod_img_7.jpg" /></div><div class="ctext-back-7"></div><img class="ctext-img-7" src="/static/images/texts/text_7.png" /></div>
            <div class="ctext ctext-8"><div class="prod_img prod_img-8"><img class="prod-img-8" src="/static/images/prod_img/1/prod_img_8.jpg" /></div><div class="ctext-back-8"></div><img class="ctext-img-8" src="/static/images/texts/text_8.png" /></div>
            <div class="ctext ctext-9"><div class="prod_img prod_img-9"><img class="prod-img-9" src="/static/images/prod_img/1/prod_img_9.jpg" /></div><div class="ctext-back-9"></div><img class="ctext-img-9" src="/static/images/texts/text_9.png" /></div>
            <div class="ctext ctext-10"><div class="ctext-back-10"></div><img class="ctext-img-10" src="/static/images/texts/text_10.png" /></div>
            
            <!-- inite page -->
            <!-- Include these scripts to import address books with CloudSponge -->
            <script type="text/javascript" src="https://api.cloudsponge.com/address_books.js"></script>
            <script type="text/javascript" charset="utf-8">
                var csPageOptions = {
                    domain_key:"SRVTZN6BK75JASSYL85M", 
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
                        var page = parseInt( window.scrollY / 920 ) + 1;
                        if (page > 10) return;
                        $.scrollTo(page * 920 + 150, 1400);
                    });
                    $('#invitations-form').submit(function(ev,data){
                        $form = $(this);
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
                    </form>
                    <div id="gifts" style="display:none;">
                        <div class="money">
                            <span>YOU'VE EARNED $60!</span>
                        </div>
                    </div>
                    <input type="text" id="personal-link" value="" onfocus="setTimeout(function(){jQuery('#personal-link').select();}, 100);"/>
                    
                    <div id="sharebox">

                        <div class="fb-share shareitem" onclick="post_on_wall();">
                            <a name="fb_share" type="icon_link" share_url=""></a>
                        </div>
                        <script type="text/javascript">
                            function post_on_wall() {
                                var personal_link = Subscriber.personal_link;
                                jQuery('.fb-share.shareitem a').attr('share_url',personal_link);
                                url = 'http://www.facebook.com/sharer.php?u='+personal_link+'&t=Meet Wonderhop !';
                                window.open(url, "Post on Wall", "width=780, height=410, toolbar=0, scrollbars=0, status=0, resizable=0, location=0, menuBar=0, left=0, top=0");
                            }
                        </script>

                        <div class="twitter-share shareitem" onclick="tweet_it();">
                            <a href= title="Share this on Twitter" id="service-links-twitter" class="service-links-twitter" rel="nofollow" target="_blank"></a>
                            <script type="text/javascript">
                                function tweet_it() {
                                    var width  = 575,
                                    height = 400,
                                    left   = (jQuery(window).width()  - width)  / 2,
                                    top    = (jQuery(window).height() - height) / 2,
                                    url    = 'http://twitter.com/share/?url=' + Subscriber.personal_link,
                                    opts   = 'status=1' +
                                            ',width='  + width  +
                                            ',height=' + height +
                                            ',top='    + top    +
                                            ',left='   + left;
                                    jQuery('.twitter-share.shareitem a').attr('href',Subscriber.personal_link);
                                    window.open(url, 'Twitter', opts);
                                    return falsse;
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

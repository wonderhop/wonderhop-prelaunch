<?php

require_once dirname(__FILE__) . '/../main.php';


if ( ! is_get() or ! isset($_GET['c']) or empty($_GET['c'])) die('Invalid Request !');

if (confirm($_GET['c'])) {
    //header('Location: ' . BASEURL);
    die('
    <html>
        <head>
            <meta http-equiv="refresh" content="3 ; '.BASEURL.'" />
        </head>
        <body>
            <p>Email Confirmed ! Redirecting in 3 seconds ...</p>
        </body>
    </html>
    ');
} else {
    die('Invalid Request Parameters !');
}

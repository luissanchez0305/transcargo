<?php

/**
 * WEB_ROOT_FOLDER is the name of the parent folder you created these 
 * documents in.
 */
define('SERVER_ROOT' , '/inetpub/wwwroot');

//yoursite.com is your webserver
define('SITE_ROOT' , 'http://localhost');

/**
 * Fetch the router
 */
require_once(SERVER_ROOT . '/controllers/' . 'router.php');

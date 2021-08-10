<?php
//ini_set('session.save_handler','files');
//ini_set('session.save_path','/tmp');
//ini_set('session.save_handler', 'redis');
//ini_set('session.save_path', 'tcp://127.0.0.1:6379');
//require realpath($_SERVER['DOCUMENT_ROOT']).'/inc/compress.inc.php';
require realpath($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php';
require realpath($_SERVER['DOCUMENT_ROOT']).'/inc/pusher.inc.php';
require realpath($_SERVER['DOCUMENT_ROOT']).'/inc/functions.inc.php';
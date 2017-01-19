<?php

ini_set('session.save_path', 'PERSISTENT=myapp_session ' . getenv('MEMCACHIER_SERVERS'));
ini_set('memcached.sess_binary', 1);
ini_set('memcached.sess_sasl_username', getenv('MEMCACHIER_USERNAME'));
ini_set('memcached.sess_sasl_password', getenv('MEMCACHIER_PASSWORD'));

session_start();
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}
$_SESSION['count']++;

echo "Hello #" . $_SESSION['count'];

?>
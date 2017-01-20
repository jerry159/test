<?php

session_start();
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}
$_SESSION['count']++;
error_log("123--->" . $_SESSION['count'] );
echo "Hello #" . $_SESSION['count'];

?>
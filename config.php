<?php 
session_start();
date_default_timezone_set('America/New_York');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'vacc_db');
define('DB_USER', 'root');
define('DB_PWD', 'babysuse');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
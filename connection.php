<?php
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'new_parking';

$con = new mysqli($host, $db_user, $db_password, $db_name);

if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
}
?>

<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$userType = ApplicationKeyValues::$USER_TYPE_ADMIN;
$typeLabel = 'admin';
include 'users.php';
?>

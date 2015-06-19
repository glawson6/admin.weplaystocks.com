<?php

include_once 'init.php'; /* load requried pages and initialized */
$loginSession = $_SESSION['loginSession'];
if (isset($loginSession)) {
    switch ($loginSession->getUserType()) {
        case ApplicationKeyValues::$USER_TYPE_SUPER_ADMIN:
        case ApplicationKeyValues::$USER_TYPE_ADMIN:
            header('location:home.php');
            break;
        case ApplicationKeyValues::$USER_TYPE_TEACHER:
        case ApplicationKeyValues::$USER_TYPE_STUDENT:
            header('location:summary.php');
            break;
        default:
            header('location:logout.php');
            break;
    }
} else {
    header('location:login.php');
}

?>


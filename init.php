<?php
phpinfo();
ob_start();
if (!isset($_SESSION)) {
    session_start();
}

function __autoload($class_name) {
    if (file_exists('business/' . $class_name . '.php')) {
        require_once('business/' . $class_name . '.php');
    } else
    if (file_exists('data/api/' . $class_name . '.php')) {
        require_once('data/api/' . $class_name . '.php');
    } else
    if (file_exists('data/mappers/' . $class_name . '.php')) {
        require_once('data/mappers/' . $class_name . '.php');
    } else
    if (file_exists('general/' . $class_name . '.php')) {
        require_once('general/' . $class_name . '.php');
    } else
    if (file_exists('contents/mail/' . $class_name . '.php')) {
        require_once('contents/mail/' . $class_name . '.php');
    } else

    if (file_exists('contents/fckeditor/' . $class_name . '.php')) {
        require_once('contents/fckeditor/' . $class_name . '.php');
    }
}

?>

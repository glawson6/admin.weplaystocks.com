<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$userController = new UserController();
$loginUser = $_SESSION['loginSession'];

$accessRight = $userController->GetAccessRight($loginUser);
$hasRight = $accessRight->getCanUpdateStudents();

if (!$hasRight) {
    header('location:logout.php');
}

if ($hasRight && isset($_POST['submit'])) {
    $errors = array();
    $userId = $userController->RegisterStudent();
    if ($userId) header("location:edit_profile.php?id=$userId");
}

$typeLabel = 'student';
include 'register_user.php';
?>

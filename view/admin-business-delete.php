<?php


require_once "../controller/BusinessController.php";
require_once "../controller/UserController.php";

session_start();
//$userId = $_SESSION['userId'];
$businessController = new BusinessController();
$userController = new UserController();

if (isset($_GET['businessId']) & isset($_GET['managerId'])) {
    $businessController->deleteBusinessEmployeeHeader($_GET['businessId'], $_GET['managerId']);
    $businessController->deleteBusiness($_GET['businessId']);
    $userController->deleteUser($_GET['managerId']);
    header("location:admin-home.php");
}

?>
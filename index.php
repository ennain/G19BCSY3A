<?php
ob_start();
require_once './init/init.php';

$user = loggedInUser();
$isAdmin = isAdmin();  

include './includes/header.inc.php';
include './includes/navbar.inc.php';



$available_pages = ['login', 'register', 'dashboard', 'logout', 'profile','user/create','user/list'];
$logged_in_pages = ['dashboard', 'profile'];
$non_logged_in_pages = ['login', 'register'];
$admin_pages = ['user/create','user/list'];

$page = '';
if (isset($_GET['page'])) {
    $page = $_GET['page']; // dashboard
}

if (in_array($page, $logged_in_pages) && empty($user)) {
    header('Location: ./?page=login');
}

if (in_array($page, $non_logged_in_pages) && !empty($user)) {
    header('Location: ./?page=dashboard');
}
if (in_array($page, $available_pages) && !empty($user)) {
    if(in_array($page, $admin_pages) && !$isAdmin) {
        header('Location: ./?page=dashboard');
    }
}

if (in_array($page, $available_pages)) {
    include './pages/' . $page . '.php';
} else {
    //header('Location: ./index.php?page=login');
    header('Location: ./?page=dashboard');
}

include './includes/footer.inc.php';
?>
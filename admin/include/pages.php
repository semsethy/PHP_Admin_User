
<?php
session_start();

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); 
}
    $page = "dashboard.php";
    $p = "dashboard.php";
    if(isset($_GET['p'])){
        $p = $_GET['p'];
        switch($p) {
            case "dashboard":
                $page = "dashboard.php";
                break;
            case "slideshow_add":
                $page = "slideshow_add.php";
                break;
            case "slideshow_list":
                $page = "slideshow_list.php";
                break;
            case "category_list":
                $page = "category_list.php";
                break;
            case "category_add":
                $page = "category_add.php";
                break;
            case "category_edit":
                $page = "category_edit.php";
                break;
            case "product_list":
                $page = "product_list.php";
                break;
            case "product_add":
                $page = "product_add.php";
                break;
            case "order":
                $page = "order.php";
                break;
            case "page":
                $page = "page.php";
                break;
            case "user":
                $page = "user.php";
                break;
            case "user_add":
                $page = "user_add.php";
                break;
            case "setting":
                $page = "setting.php";
                break;
            default:
                $page = "dashboard.php";
                break;
        }
    }
?>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <?php include 'include/nav.php'?>
  <div class="body-wrapper">
  <?php include 'include/header.php'?>
    <?php include "$page" ?>
  </div>
</div>


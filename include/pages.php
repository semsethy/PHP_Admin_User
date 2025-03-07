
<?php
    $page = "home.php";
    $p = "home.php";
    if(isset($_GET['p'])){
        $p = $_GET['p'];
        switch($p) {
            case "home":
                $page = "home.php";
                break;
            case "shop":
                $page = "shop.php";
                break;
            case "shop_details":
                $page = "shop_details.php";
                break;
            case "contact":
                $page = "contact.php";
                break;
            case "shoping_cart":
                $page = "shoping_cart.php";
                break;
            case "checkout":
                $page = "checkout.php";
                break;
            case "blog_details":
                $page = "blog_details.php";
                break;
            case "blog":
                $page = "blog.php";
                break;
            case "login":
                $page = "login.php";
                break;
            case "register":
                $page = "register.php";
                break;
            default:
                $page = "home.php";
                break;
        }
    }
?>
    <?php include "$page" ?>

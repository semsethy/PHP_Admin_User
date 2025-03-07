<?php
// Database connection
require_once 'admin/include/shopping_cartConf.php';
$shopping = new ShoppingCart();
$total_quantity = 0;
$total = 0;
// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
    $result = $shopping->getShoppingCart($user_id);
    
    // Start output buffering to capture the HTML
    ob_start();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $product_id = $row['product_id'];
        $product_name = $row['product_name'];
        $product_price = $row['price'];
        $product_image = $row['main_image_url'];
        $quantity = $row['quantity'];
        $total_price = $product_price * $quantity;
        $total += $total_price;
    
        $total_quantity += $quantity;
        ?>
        <tr>
            <td class="shoping__cart__item">
                <img style="width:80px; height:80px;" src="admin/<?php echo $product_image; ?>" alt="">
                
            </td>
            <td class="shoping__cart__item">
                
                <h5><?php echo $product_name; ?></h5>
            </td>
            <td class="shoping__cart__quantity">
                <span>x <?php echo $quantity; ?></span>
            </td>
            <td class="shoping__cart__total">
                <span>$<?php echo number_format($total_price, 2); ?></span>
            </td>
        </tr>
    
        <?php
    }
    
    // Capture the output and store in a variable
    $cart_html = ob_get_clean();
} else {
    $total_price = 0;
    $cart_html = "<tr><td colspan='4'>Your cart is empty</td></tr>";
}


?>

<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__left">
                        <ul>
                            <li><i class="fa fa-envelope"></i> sethyrisk@gmail.com</li>
                            <li>Free Shipping for all Order of $99</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-pinterest-p"></i></a>
                        </div>
                        <div class="header__top__right__language">
                            <img src="img/language.png" alt="">
                            <div>English</div>
                            <span class="arrow_carrot-down"></span>
                            <ul>
                                <li><a href="#">Khmer</a></li>
                                <li><a href="#">English</a></li>
                            </ul>
                        </div>
                        <div class="header__top__right__auth">
                            <?php if(!isset($_SESSION['user_id'])){ ?>
                                <a href="index.php?p=login"><i class="fa fa-user"></i> Login</a>
                            <?php }else{ ?>
                                <a href='logout.php'><i class='fa fa-user'></i> Logout</a>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="header__logo">
                    <a href="./index.php?p=home"><img src="img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="header__menu">
                    <ul>
                        <li class=""><a href="./index.php?p=home">Home</a></li>
                        <li><a href="./index.php?p=shop">Shop</a></li>
                        <!-- <li><a href="#">Pages</a>
                            <ul class="header__menu__dropdown">
                                <li><a href="./index.php?p=shop_details">Shop Details</a></li>
                                <li><a href="./index.php?p=shoping_cart">Shopping Cart</a></li>
                                <li><a href="./index.php?p=checkout">Check Out</a></li>
                                <li><a href="./index.php?p=blog_details">Blog Details</a></li>
                            </ul>
                        </li> -->
                        <li><a href="./index.php?p=blog">Blog</a></li>
                        <li><a href="./index.php?p=contact">Contact</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="header__cart">
                    <ul>
                        <li><a href="#"><i class="fa fa-heart"></i> <span>0</span></a></li>
                        <li id="popup"><a><i class="fa fa-shopping-bag"></i> <span id="cart-count"><?php echo $total_quantity; ?></span></a></li>
                    </ul>
                    <div class="header__cart__price">Item: <span>$<?php echo number_format($total, 2); ?></div>
                </div>
            </div>
        </div>
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>

<!-- Add to Cart Sidebar Popup -->
<div id="cart-popup" class="cart-popup">
    <div class="cart-popup-content">
        <span class="close-btn">&times;</span>
        <h2>Your Cart</h2>
        <p><?php echo $total_quantity; ?> items added to your cart.</p>
        <div class="row">
        <div class="col-lg-12">
            <div class="shoping__cart__table">
                <table>
                    
                    <tbody>
                        <?php echo $cart_html; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="shoping__checkout">
                <ul>
                    <li>Subtotal <span>$<?php echo number_format($total, 2); ?></span></li>
                </ul>
                <a href="index.php?p=shoping_cart" class="primary-btn">VIEW CART</a> </br>
                <a href="index.php?p=checkout" class="primary-btn">PROCEED TO CHECKOUT</a>
            </div>
        </div>
        </div>
    </div>
</div>

<style>
/* Sidebar Popup Styling */
.cart-popup {
    display: none;
    position: fixed;
    top: 0;
    right: -320px; /* Hidden initially */
    width: 500px;
    height: 100vh;
    background: #fff;
    box-shadow: -3px 0 10px rgba(0, 0, 0, 0.2);
    transition: right 0.3s ease-in-out;
    z-index: 1000;
    padding: 20px;
    overflow-y: auto;
}

/* Show Sidebar */
.cart-popup.show {
    right: 0;
}

/* Popup Content */
.cart-popup-content {
    text-align: center;
}

.cart-popup .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
}

.checkout-btn {
    background: #ff6600;
    color: #fff;
    padding: 10px;
    border: none;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
    border-radius: 5px;
}

.checkout-btn:hover {
    background: #e65c00;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var cartCount = document.getElementById("cart-count");

    // If the total quantity is available from PHP, update the cart count
    var totalQuantity = <?php echo $total_quantity; ?>;

    // Set the cart count element to the total quantity
    cartCount.textContent = totalQuantity;

    var popup = document.getElementById("popup");
    var cartPopup = document.getElementById("cart-popup");
    var closeBtn = document.querySelector(".close-btn");

    // Show the sidebar when clicking the cart icon
    popup.addEventListener("click", function (event) {
        event.preventDefault();
        cartPopup.style.display = "block";
        setTimeout(() => {
            cartPopup.classList.add("show");
        }, 10); // Add delay to trigger animation
    });

    // Close the sidebar when clicking the close button
    closeBtn.addEventListener("click", function () {
        cartPopup.classList.remove("show");
        setTimeout(() => {
            cartPopup.style.display = "none";
        }, 300); // Hide after animation
    });

    // Close when clicking outside the popup
    window.addEventListener("click", function (event) {
        if (event.target == cartPopup) {
            cartPopup.classList.remove("show");
            setTimeout(() => {
                cartPopup.style.display = "none";
            }, 300);
        }
    });
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get current page from the URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get("p") || "home"; // Default to 'home' if no parameter

        // Select all menu items
        const menuItems = document.querySelectorAll(".header__menu ul li");

        menuItems.forEach(item => {
            // Get the link inside each menu item
            const link = item.querySelector("a");

            // Extract the 'p' parameter from the href attribute
            if (link) {
                const linkParams = new URLSearchParams(link.getAttribute("href").split("?")[1]);
                const linkPage = linkParams.get("p");

                // If it matches the current page, add the "active" class
                if (linkPage === page) {
                    item.classList.add("active");
                } else {
                    item.classList.remove("active");
                }
            }
        });
    });
</script>
<?php
require_once 'admin/include/shopping_cartConf.php';
$shopping = new ShoppingCart();
$total = 0;
$user_id = null;
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
        ?>
        <tr>
            <td class="shoping__cart__item">
                <img style="width:20%; height:20%;" src="admin/<?php echo $product_image; ?>" alt="">
                <h5><?php echo $product_name; ?></h5>
            </td>
            <td class="shoping__cart__price">
                $<?php echo number_format($product_price, 2); ?>
            </td>
            <td class="shoping__cart__quantity">
                <div class="quantity">
                    <div class="pro-qty">
                        <input type="text" value="<?php echo $quantity; ?>" data-product-id="<?php echo $product_id; ?>">
                    </div>
                </div>
            </td>
            <td class="shoping__cart__total">
                $<?php echo number_format($total_price, 2); ?>
            </td>
            <td class="shoping__cart__item__close">
                <span class="icon_close" data-product-id="<?php echo $product_id; ?>"></span>
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
<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Products</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $cart_html; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="index.php?p=shop" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    <a style="cursor: pointer;" class="primary-btn cart-btn cart-btn-right" id="updateCartButton"> Update Cart</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Discount Codes</h5>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">APPLY COUPON</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <li>Subtotal <span>$<?php echo number_format($total, 2); ?></span></li>
                        <li>Total <span>$<?php echo number_format($total, 2); ?></span></li>
                    </ul>
                    <a href="index.php?p=checkout" class="primary-btn">PROCEED TO CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
    // Handle change in quantity
    const quantityInputs = document.querySelectorAll('.pro-qty input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const newQuantity = this.value;
            const productId = this.dataset.productId;
            if (newQuantity > 0) {
                // Update quantity in the frontend immediately
                updateQuantity(productId, newQuantity);
            } else {
                alert('Please enter a valid quantity');
            }
        });
    });

    // Update all quantities when the "Update Cart" button is clicked
    const updateCartButton = document.getElementById('updateCartButton');
    updateCartButton.addEventListener('click', function() {
        if (userId === null) {
            // If the user is not logged in, show the login modal
            return;
        }
        // Collect all updated quantities
        const updatedQuantities = [];
        quantityInputs.forEach(input => {
            const newQuantity = input.value;
            const productId = input.dataset.productId;
            if (newQuantity > 0) {
                updatedQuantities.push({ productId: productId, quantity: newQuantity });
            }
        });

        if (updatedQuantities.length > 0) {
            // Send all updated quantities to the server
            updateCart(updatedQuantities);
        } else {
            alert('Please enter valid quantities');
        }
    });

    // Delete item
    const deleteButtons = document.querySelectorAll('.icon_close');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            deleteItem(productId);
        });
    });
});

// Function to send updated quantities to the backend
function updateCart(updatedQuantities) {
    const formData = new FormData();
    formData.append('action', 'update_cart');

    updatedQuantities.forEach(item => {
        formData.append('product_id[]', item.productId);
        formData.append('new_quantity[]', item.quantity);
    });

    fetch('include/update_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // alert(data); 
        location.reload(); // Reload the page to reflect changes
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Function to delete item
function deleteItem(productId) {
    const formData = new FormData();
    formData.append('action', 'delete_item');
    formData.append('product_id', productId);

    fetch('include/update_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        location.reload(); // Reload the page to reflect changes
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


</script>

<?php
require_once 'admin/include/shopping_cartConf.php';
$shopping_cart = new ShoppingCart();
$total = 0;
$cart_html = "";
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
    $result = $shopping_cart->getShoppingCart($user_id);
    ob_start();
    if ($result->rowCount() > 0){
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $product_name = $row['product_name'];
            $product_price = $row['price'];
            $quantity = $row['quantity'];
            $total_price = $product_price * $quantity;
            $total += $total_price;
            ?>
                <li><?php echo $product_name;?><span>$<?=number_format($total_price,2);?></span></li>
            <?php
        }
    } else {
        echo "<li> No Item Added</li>";
    }
    $cart_html = ob_get_clean();
} else {
    $total_price = 0;
    $cart_html = "<li>No Item Added</li>";
}


?>
<script>
    // This script validates the form before the user can make a payment
    function validateForm() {
        var firstName = document.querySelector('input[name="first_name"]').value;
        var lastName = document.querySelector('input[name="last_name"]').value;
        var shippingAddress = document.querySelector('input[name="shipping_address"]').value;

        if (!firstName || !lastName || !shippingAddress) {
            alert("Please fill in all required fields.");
            return false;
        }
        return true;
    }

    paypal.Buttons({
        createOrder: function(data, actions) {
            if (!validateForm()) {
                return; 
            }

            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo number_format($total, 2, ".", ""); ?>' // Order Total
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                Swal.fire({
                    title: "Payment Successful!",
                    text: "Transaction completed by " + details.payer.name.given_name,
                    icon: "success",
                    showConfirmButton: false,
                    timer: 5000 // Auto-close after 5 seconds
                });
                
                // Create a form dynamically to send the POST request
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'payment_success.php';  // The action where the form will be submitted
                
                // Add the necessary hidden inputs to the form
                var orderIdInput = document.createElement('input');
                orderIdInput.type = 'hidden';
                orderIdInput.name = 'orderID';
                orderIdInput.value = data.orderID;
                form.appendChild(orderIdInput);

                var totalInput = document.createElement('input');
                totalInput.type = 'hidden';
                totalInput.name = 'total';
                totalInput.value = '<?php echo number_format($total, 2, ".", ""); ?>';
                form.appendChild(totalInput);

                var firstNameInput = document.createElement('input');
                firstNameInput.type = 'hidden';
                firstNameInput.name = 'firstName';
                firstNameInput.value = document.querySelector('input[name="first_name"]').value;
                form.appendChild(firstNameInput);

                var lastNameInput = document.createElement('input');
                lastNameInput.type = 'hidden';
                lastNameInput.name = 'lastName';
                lastNameInput.value = document.querySelector('input[name="last_name"]').value;
                form.appendChild(lastNameInput);

                var shippingAddressInput = document.createElement('input');
                shippingAddressInput.type = 'hidden';
                shippingAddressInput.name = 'shippingAddress';
                shippingAddressInput.value = document.querySelector('input[name="shipping_address"]').value;
                form.appendChild(shippingAddressInput);

                // Append the form to the document body and submit it
                document.body.appendChild(form);
                form.submit();
            });
        }
    }).render('#paypal-button-container');
</script>

<section class="checkout spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6><span class="icon_tag_alt"></span> Have a coupon? <a href="#">Click here</a> to enter your code
                </h6>
            </div>
        </div>
        <div class="checkout__form">
            <h4>Billing Details</h4>
            <form>
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Fist Name<span>*</span></p>
                                    <input type="text" name="first_name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Last Name<span>*</span></p>
                                    <input type="text" name="last_name">
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input">
                            <p>Country<span>*</span></p>
                            <input type="text">
                        </div>
                        <div class="checkout__input">
                            <p>Address<span>*</span></p>
                            <input type="text" name="shipping_address" placeholder="Street Address" class="checkout__input__add">
                            <input type="text" placeholder="Apartment, suite, unite ect (optinal)">
                        </div>
                        <div class="checkout__input">
                            <p>Town/City<span>*</span></p>
                            <input type="text">
                        </div>
                        <div class="checkout__input">
                            <p>Country/State<span>*</span></p>
                            <input type="text">
                        </div>
                        <div class="checkout__input">
                            <p>Postcode / ZIP<span>*</span></p>
                            <input type="text">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Phone<span>*</span></p>
                                    <input type="text" name="phone">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email<span>*</span></p>
                                    <input type="text" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="checkout__input__checkbox">
                            <label for="acc">
                                Create an account?
                                <input type="checkbox" id="acc">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <p>Create an account by entering the information below. If you are a returning customer
                            please login at the top of the page</p>
                        <div class="checkout__input">
                            <p>Account Password<span>*</span></p>
                            <input type="text">
                        </div>
                        <div class="checkout__input__checkbox">
                            <label for="diff-acc">
                                Ship to a different address?
                                <input type="checkbox" id="diff-acc">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="checkout__input">
                            <p>Order notes<span>*</span></p>
                            <input type="text"
                                placeholder="Notes about your order, e.g. special notes for delivery.">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Your Order</h4>
                            <div class="checkout__order__products">Products <span>Total</span></div>
                            <ul>
                                <?php echo $cart_html; ?>
                            </ul>
                            <div class="checkout__order__subtotal">Subtotal <span>$<?php echo number_format($total, 2); ?></span></div>
                            <div class="checkout__order__total">Total <span>$<?php echo number_format($total, 2); ?></span></div>
                            <div class="checkout__input__checkbox">
                                <label for="acc-or">
                                    Create an account?
                                    <input type="checkbox" id="acc-or">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adip elit, sed do eiusmod tempor incididunt
                                ut labore et dolore magna aliqua.</p>
                            <div class="checkout__input__checkbox">
                                <label for="payment">
                                    On Delivery
                                    <input type="checkbox" id="payment">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div id="paypal-button-container"></div>
                            <button type="submit" class="site-btn">PLACE ORDER</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


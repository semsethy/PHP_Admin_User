
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>You need to log in first!</h2>
        <p>Please log in to add products to your cart.</p>
        <a href="index.php?p=login" class="primary-btn">Login</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){

    // Handle add to cart button click
        $('.add-to-cart').click(function() {
            var product_id = $(this).data('product-id');
            
            if ($(this).data('qty')){
                var quantity = $(this).data('qty');
            }
            if ($('#quantity-input').val()){
                var quantity = $('#quantity-input').val();
            }
            var user_id = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;

            if(user_id === null) {
                $('#loginModal').fadeIn();
                return;
            }
            $.ajax({
                url: 'include/add_to_cart.php',  // PHP file that handles the database logic
                type: 'POST',
                data: { 
                    user_id: user_id, 
                    product_id: product_id,
                    quantity: quantity 
                },
                success: function(response) {
                    // console.log(response);
                    // if(response === 'success') {
                    //     alert('Product added to cart!');
                    // } else {
                    //     alert('Failed to add product to cart. Please try again.');
                    // }
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        });
        // Close the modal when the close button is clicked
        $('.close-btn').click(function() {
            $('#loginModal').fadeOut();
        });
        
        // Close the modal when clicking outside the modal content
        $(window).click(function(event) {
            if ($(event.target).is('#loginModal')) {
                $('#loginModal').fadeOut();
            }
        });
    });

</script>

<style>
    /* Modal Styles */
    .modal {
        display: none;  /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
        z-index: 1000; /* Make sure it's on top */
    }

    .modal-content {
        background-color: white;
        margin: 20% auto;
        padding: 50px;
        border-radius: 10px;
        width: 500px;
        text-align: center;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 25px;
        font-weight: bold;
        cursor: pointer;
    }

    .primary-btn {
        /* background-color: #ff6600; */
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .primary-btn:hover {
        /* background-color: #e65c00; */
    }
</style>
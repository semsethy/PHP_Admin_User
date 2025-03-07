<script>
    document.querySelectorAll('.fa-shopping-cart').forEach(item => {
        item.addEventListener('click', function() {
            const productId = this.closest('.featured__item').getAttribute('data-product-id');
            
            // Check if user is logged in
            const userId = <?php echo $_SESSION['user_id'] ?? 'null'; ?>;
            
            if (userId === null) {
                // Redirect user to login page
                window.location.href = 'index.php?p=login&redirect_to=' + encodeURIComponent(window.location.href);'; 
                return;
            }

            // If user is logged in, proceed to add to cart
            addToCart(productId);
        });
    });

    function addToCart(productId) {
        const userId = <?php echo $_SESSION['user_id'] ?? 'null'; ?>;
        
        const data = new FormData();
        data.append('action', 'add_to_cart');
        data.append('product_id', productId);
        data.append('user_id', userId);

        fetch('add_to_cart.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product added to cart!');
                updateCartUI(data.cartCount);
            } else {
                alert('There was an error adding the product to the cart.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    function updateCartUI(cartCount) {
        document.getElementById('cart-count').textContent = cartCount;
    }
</script>

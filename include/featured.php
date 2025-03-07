
<?php
require_once 'admin/include/productConf.php';  
require_once 'admin/include/categoryConf.php';
$product = new Product();
$category = new Category();

// Fetch categories for the filter
$categories = $category->getCategories();

// Get the category_id from the URL, if it's set
$category_id = $_GET['category_id'] ?? null;  // Optional: you can pass the category ID through a URL parameter

// Fetch products by category if category_id is provided
if ($category_id) {
    // Fetch products for the selected category with pagination (offset=0, limit=8)
    $products = $product->getProductsByCategory($category_id);  // Adjust limit as needed
} else {
    // Fetch all products if no category is selected
    $products = $product->read();  // Adjust limit as needed
}
?>

<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Featured Product</h2>
                </div>
                <div class="featured__controls">
                    <ul>
                        <!-- Filter for All Categories -->
                        <li class="<?php echo $category_id === null ? 'active' : ''; ?>" data-filter="*">All</li>
                        <?php foreach ($categories as $cat): ?>
                            <li class="<?php echo $category_id == $cat['id'] ? 'active' : ''; ?>" 
                                data-filter=".<?php echo strtolower($cat['category_name']); ?>">
                                <?php echo htmlspecialchars($cat['category_name']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <!-- Loop through each product and display it -->
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mix <?php echo strtolower($product['category_name']); ?>" 
                    data-product-id="<?php echo $product['id']; ?>">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="<?php echo "admin/".$product['main_image_url']; ?>">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="index.php?p=shop_details&id=<?php echo $product['id']; ?>"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="javascript:void(0);" class="add-to-cart" data-product-id="<?php echo $product['id']; ?>" data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
 <!-- Shopping Cart Icon -->
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a href="#"><?php echo $product['product_name']; ?></a></h6>
                            <h5>$<?php echo number_format($product['price'], 2); ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<?php include 'include/modal_addToCart.php'; ?>
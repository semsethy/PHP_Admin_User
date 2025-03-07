<?php
require_once 'admin/include/productConf.php';
$product = new Product();

// Fetch Product Data
$product_id = $_GET['id'] ?? null;
$product_detail = $product->getProductDetails($product_id);
if(!$product_detail){
    die("Product not found.");
}
$collection_images = json_decode($product_detail['collection_image_url'],true);
$category_id = $product_detail['category_id'];

$related_products_result = $product->getRelatedProducts($category_id,$product_id);
?>

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <img class="product__details__pic__item--large" style="width: 100%; height: 100%; object-fit: cover;"
                             src="admin/<?= $product_detail['main_image_url'] ?>" alt="">
                    </div>
                    <div class="product__details__pic__slider owl-carousel">
                        <?php
                        // Check if collection_images is an array and loop through each image
                        if (is_array($collection_images)) {
                            foreach ($collection_images as $image) {
                                // Make sure each image is a valid URL and print it
                                echo '<img style="width: 100%; height: 120px; object-fit: cover;" src="admin/' . htmlspecialchars($image) . '" alt="">';
                            }
                        } else {
                            echo '<p>No collection images available.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?= $product_detail['product_name'] ?></h3>
                    <div class="product__details__rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-half-o"></i>
                        <span>(18 reviews)</span>
                    </div>
                    <div class="product__details__price">$<?= $product_detail['price'] ?></div>
                    <p><?= $product_detail['description'] ?></p>
                    <div class="product__details__quantity">
                        <div class="quantity">
                            <div class="pro-qty">
                                <input type="text" value="1" id="quantity-input">
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="primary-btn add-to-cart"  data-product-id="<?php echo $product_detail['id']; ?>">ADD TO CART</a>
                    <ul>
                        <li><b>Availability</b> <span><?= $product_detail['stock_quantity'] ?></span></li>
                        <li><b>Shipping</b> <span>01 day shipping. <samp>Free pickup today</samp></span></li>
                        <li><b>Category</b> <span><?= $product_detail['category_name'] ?></span></li>
                        <li><b>Share on</b>
                            <div class="share">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                                    aria-selected="false">Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                    aria-selected="false">Reviews <span>(1)</span></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Products Infomation</h6>
                                    <p>Vestibulum.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Products Infomation</h6>
                                    <p>Vestibulum.</p>
                                    <p>Praesent sapien.</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Products Infomation</h6>
                                    <p>Vestibulum.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>
<!-- Product Details Section End -->

<!-- Related Product Section Begin -->
<section class="related-product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title related__product__title">
                    <h2>Related Product</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            // Check if related products are found
            if (!empty($related_products_result)) {
                foreach ($related_products_result as $related_product) {
                    echo '<div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="admin/' . $related_product['main_image_url'] . '">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="index.php?p=shop_details&id='.$related_product['id'].'"><i class="fa fa-retweet"></i></a></li>
                                        <li><a href="javascript:void(0);" class="add-to-cart" data-product-id="'.$related_product['id'].'" data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="#">' . htmlspecialchars($related_product['product_name']) . '</a></h6>
                                    <h5>$' . number_format($related_product['price'], 2) . '</h5>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo "<p class='col-12 text-center'>No related products found.</p>";
            }
            ?>
        </div>
    </div>
</section>
<!-- Related Product Section End -->


<?php include 'include/modal_addToCart.php'; ?>
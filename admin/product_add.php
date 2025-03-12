<?php
require_once 'include/productConf.php';

$product = new Product();
$product_datas = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = htmlspecialchars($_POST['product_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $status = ($_POST['status'] == 'active') ? 1 : 0;
    $category_id = intval($_POST['category_id']);
    $upload_dir = "images/products/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle Main Image
    $main_image_url = "";
    if (!empty($_FILES['product_image']['name']) && $_FILES['product_image']['error'] == 0) {
        // Delete old image if a new one is uploaded
        if (!empty($_POST['existing_main_image'])) {
            $old_image = $_POST['existing_main_image'];
            if (file_exists($old_image)) {
                unlink($old_image); // Delete old image
            }
        }

        $file_ext = pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION);
        $main_image_url = $upload_dir . uniqid() . "." . $file_ext;
        if (!move_uploaded_file($_FILES["product_image"]["tmp_name"], $main_image_url)) {
            die("Error uploading main image.");
        }
    } else {
        $main_image_url = $_POST['existing_main_image'] ?? null;
    }

    // Handle Collection Images
    $collection_images = [];
    if (!empty($_FILES['collection_images']['name'][0])) {
        // Delete old collection images if new ones are uploaded
        if (!empty($_POST['existing_collection_image'])) {
            $existing_images = json_decode($_POST['existing_collection_image'], true);
            foreach ($existing_images as $image) {
                if (file_exists($image)) {
                    unlink($image); // Delete old image
                }
            }
        }

        foreach ($_FILES['collection_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['collection_images']['size'][$key] <= 200097152) { // 20MB limit
                $file_ext = pathinfo($_FILES['collection_images']['name'][$key], PATHINFO_EXTENSION);
                $target_file = $upload_dir . uniqid() . "." . $file_ext;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $collection_images[] = $target_file;
                }
            } else {
                die("Image size exceeds 20MB.");
            }
        }
    } else {
        $collection_images = !empty($_POST['existing_collection_image']) ? json_decode($_POST['existing_collection_image'], true) : [];
    }

    $collection_image_url = json_encode($collection_images);

    if (!empty($product_name)) {
        if (isset($_POST['product_id']) && $_POST['product_id'] != '') {
            $product_id = $_POST['product_id'];
            if ($product->update($product_name, $description, $price, $stock, $status, $main_image_url, $collection_image_url, $category_id, $product_id)) {
                echo "<div id='success-alert' class='container mt-4 alert alert-success'>Product updated successfully!</div>";
                echo "<script>setTimeout(function(){ $('#success-alert').fadeOut(1000); }, 2000);</script>";
            } else {
                echo "<div id='error-alert' class='alert alert-danger'>Error updating product!</div>";
                echo "<script>setTimeout(function(){ $('#error-alert').fadeOut(1000); }, 2000);</script>";
            }
        } else {
            if ($product->create($product_name, $description, $price, $stock, $status, $main_image_url, $collection_image_url, $category_id)) {
                echo "<div id='success-alert' class='container mt-4 alert alert-success'>New product added successfully!</div>";
                echo "<script>setTimeout(function(){ $('#success-alert').fadeOut(1000); }, 2000);</script>";
            } else {
                echo "<div id='error-alert' class='alert alert-danger'>Error adding product!</div>";
                echo "<script>setTimeout(function(){ $('#error-alert').fadeOut(1000); }, 2000);</script>";
            }
        }
    } else {
        echo "<div class='alert alert-warning'>Product name cannot be empty.</div>";
    }
}
?>
<?php
$product_datas = null;
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product_data = $product->getById($product_id);
    if (count($product_data) > 0) {
        foreach ($product_data as $row) {
            $product_datas = $row;
        }
    }
}
?>
<div class="container-fluid">
    <h1><?php echo isset($product_datas['id']) ? "Edit Product" : "Add New Product"; ?></h1>
    <div class="card p-4">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo isset($product_datas['id']) ? $product_datas['id'] : ''; ?>" />
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-control"
                    value="<?php echo isset($product_datas['product_name']) ? $product_datas['product_name'] : ''; ?>" 
                    placeholder="Enter product name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="Enter product description" rows="4" required><?php echo isset($product_datas['description']) ? $product_datas['description'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <?php
                    require_once 'include/categoryConf.php';
                    $category = new Category();
                    $categorys = $category->read();
                    foreach ($categorys as $row) {
                        $selected = (isset($product_datas['category_id']) && $product_datas['category_id'] == $row['id']) ? 'selected' : '';
                        echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['category_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" placeholder="Enter product price" 
                    value="<?php echo isset($product_datas['price']) ? $product_datas['price'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock Quantity</label>
                <input type="number" name="stock" id="stock" class="form-control" placeholder="Enter stock quantity"
                    value="<?php echo isset($product_datas['stock_quantity']) ? $product_datas['stock_quantity'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Main Image</label>
                <input type="file" name="product_image" id="product_image" class="form-control" accept="image/*" 
                    onchange="previewMainImage(event)">
                <div id="main_image_preview" style="margin-top: 20px; margin-left: 20px;">
                    <img src="<?php echo isset($product_datas['main_image_url']) && !empty($product_datas['main_image_url']) ? $product_datas['main_image_url'] : 'images/logos/placeholder2.jpg'; ?>" 
                        alt="Main Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                </div> 
                <input type="hidden" name="existing_main_image" value="<?php echo htmlspecialchars($product_datas['main_image_url'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="collection_images" class="form-label">Collection Images</label>
                <input type="file" name="collection_images[]" id="collection_images" class="form-control" accept="image/*" multiple onchange="previewCollectionImages(event)">
                <div id="collection_images_preview" style="margin-top: 20px; margin-left: 20px;">
                <?php
                    
                    if (isset($product_datas['collection_image_url']) && !empty($product_datas['collection_image_url'])) {
                        $existing_images = json_decode($product_datas['collection_image_url'], true);
                        
                        
                        if (is_array($existing_images)) {
                            foreach ($existing_images as $image) {
                                echo '<img src="' . htmlspecialchars($image) . '" alt="collection image" style="margin-right: 10px;width: 80px; height: 80px;object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">';
                            }
                        } else {
                            
                            echo "<img class='current-image' src='images/logos/placeholder2.jpg' alt='Current Category Image' style='width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;'>";
                        }
                    } else {
                        echo "<img class='current-image' src='images/logos/placeholder2.jpg' alt='Current Category Image' style=' width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;'>";
                    }
                ?>
                </div>
                <input type="hidden" name="existing_collection_image" value="<?php echo htmlspecialchars(json_encode($existing_images) ?? []); ?>">

            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" <?php echo (isset($product_datas['status']) && $product_datas['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo (isset($product_datas['status']) && $product_datas['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>


<script>
    function previewMainImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('main_image_preview');
            output.innerHTML = '<img src="' + reader.result + '" alt="Main Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewCollectionImages(event) {
        var previewContainer = document.getElementById('collection_images_preview');
        previewContainer.innerHTML = ''; 

        Array.from(event.target.files).forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imageElement = document.createElement('img');
                imageElement.src = e.target.result;
                imageElement.style.width = '80px';
                imageElement.style.height = '80px';
                imageElement.style.objectFit = 'cover';
                imageElement.style.margin = '5px';
                imageElement.style.borderRadius = '5px';
                imageElement.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
                previewContainer.appendChild(imageElement);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
<script>
    function previewMainImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('main_image_preview');
            output.innerHTML = '<img src="' + reader.result + '" alt="Main Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewCollectionImages(event) {
        var previewContainer = document.getElementById('collection_images_preview');
        
        
        var existingImages = previewContainer.innerHTML;

        Array.from(event.target.files).forEach(function (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var imageElement = document.createElement('img');
                imageElement.src = e.target.result;
                imageElement.style.width = '80px';
                imageElement.style.height = '80px';
                imageElement.style.objectFit = 'cover';
                imageElement.style.margin = '5px';
                imageElement.style.borderRadius = '5px';
                imageElement.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';

                previewContainer.appendChild(imageElement);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
<?php
    require_once 'include/categoryConf.php';
    $categories = new Category();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $category_name = trim($_POST['category_name']);
    $category_status = (int) $_POST['category_status'];

    
    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] == 0) {
        
        $image_dir = 'images/categories/';
        
        $image_name = basename($_FILES['category_image']['name']);
        $image_tmp_name = $_FILES['category_image']['tmp_name'];
        $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png'];

        
        if (in_array(strtolower($image_type), $allowed_types)) {
            
            $unique_image_name = uniqid() . '.' . $image_type;
            
            
            $image_path = $image_dir . $unique_image_name;
            
            
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $category_image = $image_path; 
            } else {
                echo "<div class='alert alert-danger'>Error uploading image.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Only JPG, JPEG, and PNG files are allowed for the image.</div>";
        }
    } else {
        $category_image = $_POST['existing_image'] ?? null;
    }
    if (!empty($category_name)) {
        if (isset($_POST['category_id']) && $_POST['category_id'] != '') {
            $category_id = $_POST['category_id'];
            $stmt = $categories->update($category_name, $category_status, $category_image, $category_id);
            $action_message = "updated successfully!";
        } else {
            $stmt = $categories->create($category_name, $category_status, $category_image);
            $action_message = "New category added successfully!";
        }

        if ($stmt->execute()) {
            echo "<div id='success-alert' style='width:80%;' class='container alert alert-success mt-4 mb-4'>$action_message</div>";
            
            echo "<script>setTimeout(function(){ $('#success-alert').fadeOut(1000); }, 2000);</script>";
        } else {
            echo "<div id='error-alert' class='alert alert-danger'>Error: " . $stmt->errorInfo()[2] . "</div>";
            echo "<script>setTimeout(function(){ $('#error-alert').fadeOut(1000); }, 2000);</script>";
        }
    } else {
        echo "<div class='alert alert-warning'>Category name cannot be empty.</div>";
    }
}
?>

<?php
$category = null;
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $category = $categories->getCategoriesByID($category_id);
    if (!$category) {
        echo "Category not found.";
    }
}
?>

<div class="container-fluid">
    <h1><?php echo isset($category) ? 'Edit Category' : 'Add New Category'; ?></h1>
    <div class="card p-3">
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo isset($category) ? htmlspecialchars($category['category_name']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="category_image" class="form-label">Category Image</label>
                <input type="file" class="form-control" id="category_image" name="category_image" accept="image/*" onchange="previewImage(event)">
                
                <?php if (isset($category)): ?>
                    <div class="mt-2">Current image: </div>
                    <div id="image_preview">
                        <?php if (isset($category['category_image']) && !empty($category['category_image'])): ?>
                            <img class="current-image" src="<?php echo htmlspecialchars($category['category_image']); ?>" alt="Current Category Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php else: ?>
                            <img class="current-image" src="images/logos/placeholder2.jpg" alt="Current Category Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($category['category_image']); ?>">
                <?php else: ?>
                   
                    <div id="image_preview">
                        <img class="current-image" src="images/logos/placeholder2.jpg" alt="Current Category Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="category_status" class="form-label">Status</label>
                <select class="form-select" id="category_status" name="category_status" required>
                    <option value="1" <?php echo isset($category) && $category['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo isset($category) && $category['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <?php if (isset($category)): ?>
                <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><?php echo isset($category) ? 'Save Changes' : 'Add Category'; ?></button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image_preview');
            output.innerHTML = '<img src="' + reader.result + '" alt="Selected Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.3);">';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

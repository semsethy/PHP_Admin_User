

<?php
    require_once 'include/slideshowConf.php';
    $slideshows = new Slideshow();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $image = $_FILES['image']['name'];
        $category_id = intval($_POST['category_id']);
        $caption = $_POST['caption'];
        $description = $_POST['description'];
        $link = $_POST['link'];
        $status = $_POST['status'];
        $image_path = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_dir = "images/slideshows/";
            $image_name = basename($_FILES['image']['name']);
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_types = ['jpg', 'jpeg', 'png'];
            if (in_array(strtolower($image_type), $allowed_types)) {
                $unique_image_name = uniqid() . '.' . $image_type;
                $image_path = $image_dir . $unique_image_name;
                if (!move_uploaded_file($image_tmp_name, $image_path)) {
                    echo "<div class='alert alert-danger'>Error uploading image.</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>Only JPG, JPEG, and PNG files are allowed for the image.</div>";
            }
        } else {
            $image_path = $_POST['existing_image'] ?? null;
        }

        if (!empty($title)) {
            if (isset($_POST['slideshow_id']) && $_POST['slideshow_id'] != '') {
                $slideshow_id = $_POST['slideshow_id'];
                $stmt = $slideshows->update($title, $image_path, $caption, $description, $link, $status, $category_id, $slideshow_id);
                $action_message = "Updated successfully!";
            } else {
                $stmt = $slideshows->create($title, $image_path, $caption, $description, $link, $status, $category_id);
                $action_message = "New slideshow added successfully!";
            }

            if ($stmt->execute()) {
                echo "<div id='success-alert' style='width:80%;' class='container alert alert-success mt-4 mb-4'>$action_message</div>";
                echo "<script>setTimeout(function(){ $('#success-alert').fadeOut(1000); }, 2000);</script>";
            } else {
                echo "<div id='error-alert' class='alert alert-danger'>Error: " . $stmt->error . "</div>";
                echo "<script>setTimeout(function(){ $('#error-alert').fadeOut(1000); }, 2000);</script>";
            }
        } else {
            echo "<div class='alert alert-warning'>Title cannot be empty.</div>";
        }
    }
?>
<?php
$slideshow = null;
if (isset($_GET['id'])) {
    $slideshow_id = $_GET['id'];
    $slideshow = $slideshows->getSlideshowByID($slideshow_id);
    if (!$slideshow) {
        echo "Slideshow not found.";
    }
}
?>

<div class="container-fluid">
    <div class="card p-3 mt-5">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="slideshow_id" value="<?php echo isset($slideshow['id']) ? $slideshow['id'] : ''; ?>" />
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($slideshow) ? htmlspecialchars($slideshow['title']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                
                <?php if (isset($slideshow)): ?>
                    <div class="mt-2">Current image: </div>
                    <div id="image_preview">
                        <?php if (isset($slideshow['image']) && !empty($slideshow['image'])): ?>
                            <img class="current-image" src="<?php echo htmlspecialchars($slideshow['image']); ?>" alt="Current Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php else: ?>
                            <img class="current-image" src="images/logos/placeholder2.jpg" alt="Current Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($slideshow['image']); ?>">
                <?php else: ?>
                    <div id="image_preview">
                        <img class="current-image" src="images/logos/placeholder2.jpg" alt="Placeholder Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                    </div>
                <?php endif; ?>
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
                <label for="caption" class="form-label">Caption</label>
                <input type="text" class="form-control" id="caption" name="caption" value="<?php echo isset($slideshow) ? htmlspecialchars($slideshow['caption']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo isset($slideshow) ? htmlspecialchars($slideshow['description']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="link" class="form-label">Link</label>
                <input class="form-control" type="text" name="link" id="link" value="<?php echo isset($slideshow) ? htmlspecialchars($slideshow['link']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="1" <?php echo isset($slideshow) && $slideshow['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo isset($slideshow) && $slideshow['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <?php if (isset($slideshow)): ?>
                <input type="hidden" name="id" value="<?php echo $slideshow['id']; ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><?php echo isset($slideshow) ? 'Save Changes' : 'Save Slide'; ?></button>
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

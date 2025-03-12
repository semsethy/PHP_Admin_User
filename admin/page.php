<?php
require_once 'include/settingConf.php';
$setting = new Setting();

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = trim($_POST['title']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $facebook_link = trim($_POST['facebook_link']);
    $instagram_link = trim($_POST['instagram_link']);
    $twitter_link = trim($_POST['twitter_link']);
    $image_dir = 'images/logos/';

    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
        $image_name = basename($_FILES['icon']['name']);
        $image_tmp_name = $_FILES['icon']['tmp_name'];
        $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png'];

        // Validate image file type
        if (in_array(strtolower($image_type), $allowed_types)) {
            $unique_image_name = uniqid() . '.' . $image_type;
            $image_path = $image_dir . $unique_image_name;

            // Check if an old logo exists and delete it
            if (!empty($_POST['existing_icon_image'])) {
                $existing_logo = $_POST['existing_icon_image'];
                if (file_exists($existing_logo)) {
                    unlink($existing_logo);  // Delete the old logo file
                }
            }

            // Move the uploaded file to the server directory
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $icon = $image_path;
            } else {
                echo "<div class='alert alert-danger'>Error uploading logo image.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Only JPG, JPEG, and PNG files are allowed for the logo image.</div>";
        }
    } else {
        // If no new logo is uploaded, retain the existing image if it's set
        $icon = $_POST['existing_icon_image'] ?? null;
    }

    // Handle the logo image upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $image_name = basename($_FILES['logo']['name']);
        $image_tmp_name = $_FILES['logo']['tmp_name'];
        $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png'];

        // Validate image file type
        if (in_array(strtolower($image_type), $allowed_types)) {
            $unique_image_name = uniqid() . '.' . $image_type;
            $image_path = $image_dir . $unique_image_name;

            // Check if an old logo exists and delete it
            if (!empty($_POST['existing_logo_image'])) {
                $existing_logo = $_POST['existing_logo_image'];
                if (file_exists($existing_logo)) {
                    unlink($existing_logo);  // Delete the old logo file
                }
            }

            // Move the uploaded file to the server directory
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $logo = $image_path;
            } else {
                echo "<div class='alert alert-danger'>Error uploading logo image.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Only JPG, JPEG, and PNG files are allowed for the logo image.</div>";
        }
    } else {
        // If no new logo is uploaded, retain the existing image if it's set
        $logo = $_POST['existing_logo_image'] ?? null;
    }

    // Save the data to the database
    if (!empty($email) && !empty($phone_number)) {
        $stmt = $setting->saveSettings($title, $icon, $email, $phone_number, $facebook_link, $instagram_link, $twitter_link, $logo);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<div id='success-alert' class='alert alert-success'>Settings saved successfully!</div>";
            echo "<script>setTimeout(function(){ $('#success-alert').fadeOut(1000); }, 2000);</script>";
        } else {
            echo "<div id='error-alert' class='alert alert-danger'>Error: " . $stmt->errorInfo()[2] . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>All fields are required.</div>";
    }
}

// Fetch the existing settings from the database
$settings = $setting->getSettings(); // Assuming getSettings fetches the current settings
?>

<div class="container-fluid">
    <h1>Website Settings</h1>
    <div class="card p-3 mt-5">
        <form action="" method="POST" enctype="multipart/form-data">
            
            <!-- Title -->
            <div class="mb-3">
                <label for="text" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($settings) ? htmlspecialchars($settings['title']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="icon" class="form-label">Icon</label>
                <input type="file" class="form-control" id="icon" name="icon" accept="image/*" onchange="previewImage(event)">
                
                <?php if (isset($settings)): ?>
                    <div class="mt-2">Current Icon: </div>
                    <div id="image_preview">
                        <?php if (isset($settings['icon']) && !empty($settings['icon'])): ?>
                            <img class="current-image" src="<?php echo htmlspecialchars($settings['icon']); ?>" alt="Current Logo Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php else: ?>
                            <img class="current-image" src="images/logos/placeholder2.jpg" alt="Placeholder Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="existing_icon_image" value="<?php echo htmlspecialchars($settings['icon']); ?>">
                <?php else: ?>
                    <div id="image_preview">
                        <img class="current-image" src="images/logos/placeholder2.jpg" alt="Current Category Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*" onchange="previewImage(event)">
                
                <?php if (isset($settings)): ?>
                    <div class="mt-2">Current Logo: </div>
                    <div id="image_preview">
                        <?php if (isset($settings['logo']) && !empty($settings['logo'])): ?>
                            <img class="current-image" src="<?php echo htmlspecialchars($settings['logo']); ?>" alt="Current Logo Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php else: ?>
                            <img class="current-image" src="images/logos/placeholder2.jpg" alt="Placeholder Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="existing_logo_image" value="<?php echo htmlspecialchars($settings['logo']); ?>">
                <?php else: ?>
                    <div id="image_preview">
                        <img class="current-image" src="images/logos/placeholder2.jpg" alt="Current Category Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); border: 1px solid lightgray;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($settings) ? htmlspecialchars($settings['email']) : ''; ?>" required>
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo isset($settings) ? htmlspecialchars($settings['phone_number']) : ''; ?>" required>
            </div>

            <!-- Other Fields -->
            <div class="mb-3">
                <label for="facebook_link" class="form-label">Facebook Link</label>
                <input type="text" class="form-control" id="facebook_link" name="facebook_link" value="<?php echo isset($settings) ? htmlspecialchars($settings['facebook_link']) : ''; ?>">
            </div>

            <div class="mb-3">
                <label for="instagram_link" class="form-label">Instagram Link</label>
                <input type="text" class="form-control" id="instagram_link" name="instagram_link" value="<?php echo isset($settings) ? htmlspecialchars($settings['instagram_link']) : ''; ?>">
            </div>

            <div class="mb-3">
                <label for="twitter_link" class="form-label">Twitter Link</label>
                <input type="text" class="form-control" id="twitter_link" name="twitter_link" value="<?php echo isset($settings) ? htmlspecialchars($settings['twitter_link']) : ''; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<script>
    // Image preview function
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image_preview');
            output.innerHTML = '<img src="' + reader.result + '" alt="Selected Image" style="margin-top:20px; margin-left:20px; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.3);">';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

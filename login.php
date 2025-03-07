<!-- login.php -->
<?php
// session_start();
require_once 'admin/include/userConf.php';

// Create instance of the User class
$userClass = new User();

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Fetch user from the database using User class method
    $select = $userClass->getUsersByEmail($email);

    if (!empty($select)) {
        $row = $select[0]; // Assuming the first result is the user
        if (password_verify($pass, $row['password'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            $userClass->updateLastLogin($row['id']);
            $_SESSION['user_id'] = $row['id'];

            // Ensure the redirect URL is safe
            $redirectUrl = $_SESSION['redirect_url'] ?? 'index.php?p=home'; // Default to home if not set
            unset($_SESSION['redirect_url']); // Remove redirect URL after use

            // Check if the redirect URL is valid
            if (filter_var($redirectUrl, FILTER_VALIDATE_URL) && strpos($redirectUrl, $_SERVER['HTTP_HOST']) === false) {
                $redirectUrl = 'index.php?p=home'; // Fallback to a safe page
            }

            header('Location: ' . $redirectUrl);
            exit();
        } else {
            $message[] = 'Incorrect password!';
            $shake = true; // Trigger shake effect
        }
    } else {
        $message[] = 'User not found!';
        $shake = false; // No shake needed
    }
}

// Capture the page URL where the user was trying to go (e.g., add to cart page)
if (isset($_GET['redirect_to'])) {
    $_SESSION['redirect_url'] = $_GET['redirect_to'];
}
?>


<?php include "include/userstyle.php" ?>
<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6 col-xxl-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="index.php?p=home" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                <img src="admin/images/logos/dark-logo.svg" width="180" alt="">
                            </a>
                            <p class="text-center">Your Social Campaigns</p>

                            <!-- Display message if there is an error -->
                            <?php if (isset($message)): ?>
                                <div class="alert alert-danger">
                                    <?php echo implode('<br>', $message); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Login Form -->
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" required>
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <input type="password" class="form-control <?php echo isset($shake) && $shake ? 'shake' : ''; ?>" id="exampleInputPassword1" name="password" required>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                                        <label class="form-check-label text-dark" for="flexCheckChecked">
                                            Remember this Device
                                        </label>
                                    </div>
                                    <a class="text-primary fw-bold" href="#">Forgot Password ?</a>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
                                <div class="d-flex align-items-center justify-content-center">
                                    <p class="fs-4 mb-0 fw-bold">New to Modernize?</p>
                                    <a class="text-primary fw-bold ms-2" href="index.php?p=register">Create an account</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shake Animation CSS -->
<style>
    .shake {
        animation: shake 0.3s ease-in-out 0s 1;
        border: 1px solid orange;
    }

    @keyframes shake {
        0% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-5px);
        }
        50% {
            transform: translateX(5px);
        }
        75% {
            transform: translateX(-5px);
        }
        100% {
            transform: translateX(0);
        }
    }
</style>




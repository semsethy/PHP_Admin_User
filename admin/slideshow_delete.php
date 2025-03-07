<?php
require_once 'include/slideshowConf.php';
$slideshow = new Slideshow();
$slideshow_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($slideshow_id > 0) {
    $stmt = $slideshow->delete($slideshow_id);
    if ($stmt) {
        header("Location: index.php?p=slideshow_list");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Invalid slideshow ID.</div>";
}
?>

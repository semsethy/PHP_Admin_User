<?php
    require_once 'admin/include/categoryConf.php';
    $category = new Category();
    $categories = $category->getCategories();
?>

<section class="categories">
    <div class="container">
        <div class="row">
            <div class="categories__slider owl-carousel">
                <?php foreach ($categories as $row):?>
                    <div class="col-lg-3">
                    <div class="categories__item set-bg" data-setbg="<?= 'admin/' . $row['category_image']; ?>">
                    <h5><a href="index.php?p=shop&id=<?=$row['id'];?>"> <?php echo htmlspecialchars($row['category_name']); ?> </a></h5>
                    </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</section>


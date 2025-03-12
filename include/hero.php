
<?php
require_once 'admin/include/categoryConf.php';
require_once 'admin/include/slideshowConf.php';
require_once 'admin/include/settingConf.php';
$setting = new Setting();
$category = new Category();
$slideshow = new Slideshow();
$categories = $category->getCategories();
$slideshows = $slideshow->read();
$settings = $setting->getSettings();
?>
<section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>All Categories</span>
                    </div>
                    <ul>
                        <?php foreach($categories as $cat): ?>
                            <li><a href="index.php?p=shop&id=<?=$cat['id'];?>"><?php echo $cat['category_name'];?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="#">
                            <input type="text" placeholder="What do you need?">
                            <button type="submit" class="site-btn">SEARCH</button>
                        </form>
                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="hero__search__phone__text">
                            <h5><?php echo  htmlspecialchars($settings['phone_number']); ?></h5>
                            <span>support 24/7 time</span>
                        </div>
                    </div>
                </div>
                
                <div class="hero__slideshow">
                    <?php foreach ($slideshows as $slide):?>
                        <div class="hero__item set-bg" data-setbg="<?='admin/' . $slide['image'];?>">
                            <div class="hero__text">
                                <span><?=$slide['title'];?></span>
                                <h2><?=$slide['category_name'];?> <br /><?=$slide['caption'];?></h2>
                                <p><?=$slide['description'];?></p>
                                <a href="index.php?p=shop&id=<? echo $slide['category_id'];?>" class="primary-btn">SHOP NOW</a>
                            </div>
                        </div>
                    <?php endforeach;?>
                    <div class="hero__indicators">
                    <?php foreach ($slideshows as $slide):?>
                        <span class="indicator"></span>
                        <?php endforeach;?>
                    </div>
                    <div class="hero__nav">
                        <button class="prev-btn"><i class="fa fa-chevron-left"></i></button>
                        <button class="next-btn"><i class="fa fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<style>
    /* Style for the hero section */
.hero__slideshow {
    position: relative;
    width: 100%;
    height: 40vh;
    overflow: hidden;
}

.hero__item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    background-size: cover;
    background-position: center center;
}

.hero__item.active {
    opacity: 1;
}

.hero__item.set-bg {
    background-size: cover;
    background-position: center center;
}

.hero__text {
    color: white;
    position: absolute;
    top: 50%;
    left: 10%;
    transform: translate(-10%, -50%);
    text-align: left;
}

/* Style for the indicator dots */
.hero__indicators {
    position: absolute;
    bottom: 20px;  /* Make sure it's positioned at the bottom of the slide */
    left: 50%;
    transform: translateX(-50%);  /* Center the dots horizontally */
    display: flex;
    gap: 10px;
    z-index: 10;  /* Ensure it stays above the hero image */
}

.indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #c5d1b4;
    transition: background-color 0.3s;
    cursor: pointer;
}

.indicator.active {
    background-color: #7fad39;  /* Highlight active dot */
}
/* Add style for the navigation buttons */
.hero__nav {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    justify-content: space-between;
    width: 100%;
    z-index: 15;
}

.hero__nav button {
    background-color: #7fad39;
    border: none;
    color: white;
    font-size: 1rem;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.hero__nav button:hover {
    background-color: rgba(0, 0, 0, 0.5);
}

.hero__nav .prev-btn {
    left: 10%;
}

.hero__nav .next-btn {
    right: 10%;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.hero__item');
    const indicators = document.querySelectorAll('.indicator');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentIndex = 0;

    function fadeItems() {
        // Remove active class from the previous item
        items[currentIndex].classList.remove('active');
        indicators[currentIndex].classList.remove('active');
        
        // Move to the next item
        currentIndex = (currentIndex + 1) % items.length;

        // Add active class to the current item
        items[currentIndex].classList.add('active');
        indicators[currentIndex].classList.add('active');
    }
    // Manually navigate to the previous slide
    prevBtn.addEventListener('click', function() {
        items[currentIndex].classList.remove('active');
        indicators[currentIndex].classList.remove('active');
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        items[currentIndex].classList.add('active');
        indicators[currentIndex].classList.add('active');
    });

    // Manually navigate to the next slide
    nextBtn.addEventListener('click', function() {
        fadeItems();  // Reuse the fadeItems function for next slide
    });
    // Initially show the first item
    items[currentIndex].classList.add('active');
    indicators[currentIndex].classList.add('active');

    // Change slides every 5 seconds
    setInterval(fadeItems, 5000);
});

</script>

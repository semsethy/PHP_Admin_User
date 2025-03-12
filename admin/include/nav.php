
<?php
require_once 'include/settingConf.php';
$setting = new Setting();

$settings = $setting->getSettings();
?>

<style>
.sidebar-sub {
  display: none;
  padding-left: 20px;
}
.sidebar-sub.show {
  display: block;
}
.sidebar-left {
  display: flex;
  align-items: center;
  gap: 14px; 
}
.sidebar-toggle {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.toggle-icon {
  transition: transform 0.3s ease;
}
.sidebar-item.active .toggle-icon i {
  transform: rotate(180deg);
}
.logo-img img{
  height:100px;
  margin-top:20px;
}
</style>

<aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.php" class="text-nowrap logo-img">
            <img src="<?php echo  htmlspecialchars($settings['logo']); ?>" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=dashboard" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=slideshow_list" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Slideshow List</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=slideshow_add" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Add Slideshow</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Category</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=category_list" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Category List</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=category_add" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Add Category</span>
              </a>
            </li>
            <!-- <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Brand</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=brand_list" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Brand List</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=brand_add" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Add Brand</span>
              </a>
            </li> -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Product</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=product_list" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Product List</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=product_add" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Add Product</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=order" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Orders</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Configuration</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=page" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Page</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=user" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">User</span>
              </a>
            </li>
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=user_add" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Add New User</span>
              </a>
            </li> -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php?p=setting" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Setting</span>
              </a>
            </li>
        </nav>
      </div>
    </aside>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggles = document.querySelectorAll(".sidebar-toggle");

    sidebarToggles.forEach(toggle => {
      toggle.addEventListener("click", function (event) {
        event.preventDefault();

        const parentItem = this.closest(".sidebar-item");
        const submenu = parentItem.querySelector(".sidebar-sub");
        const icon = parentItem.querySelector(".toggle-icon i");

        if (submenu) {
          submenu.classList.toggle("show");
          parentItem.classList.toggle("active");

          // Close other submenus
          document.querySelectorAll(".sidebar-item").forEach(item => {
            if (item !== parentItem) {
              item.classList.remove("active");
              const sub = item.querySelector(".sidebar-sub");
              if (sub) sub.classList.remove("show");
              const otherIcon = item.querySelector(".toggle-icon i");
              if (otherIcon) otherIcon.style.transform = "rotate(0deg)";
            }
          });

          // Rotate icon
          icon.style.transform = submenu.classList.contains("show") ? "rotate(180deg)" : "rotate(0deg)";
        }
      });
    });
  });

</script>
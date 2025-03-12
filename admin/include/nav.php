<?php
require_once 'include/settingConf.php';
$setting = new Setting();
$settings = $setting->getSettings();
?>

<aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-center">
          <a href="./index.php" class="text-nowrap logo-img">
            <img src="<?php echo  htmlspecialchars($settings['logo']); ?>" style="height:100px;margin-top:20px;" alt="" />
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

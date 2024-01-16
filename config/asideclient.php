<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="#" class="app-brand-link">
      <span class="app-brand-logo demo me-1">

        <img src="../assets/logonew.png" style="width: 4rem;" alt="" srcset="">
      </span>
      <span class="app-brand-text demo menu-text fw-semibold ms-2">Fresh Way</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboards -->
    <li class="menu-item <?php echo ($pageName == "Dashboard") ? 'active' : '' ?>">
      <a href="clientdash.php" class="menu-link ">
        <i class="menu-icon tf-icons mdi mdi-home-outline "></i>
        <div>Dashboard</div>
      </a>
    </li>

    <!-- DIVIDER  -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Orders & Sales</span>
    </li>
    <!-- ORDERS  -->

    <li class="menu-item  <?php echo ($pageName == "New Order") ? 'active' : '' ?>">
      <a href="neworderclient.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-plus"></i>
        <div>New Order</div>
      </a>
    </li>
    <li class="menu-item  <?php echo ($pageName == "Orders List") ? 'active' : '' ?>">
      <a href="ordersclient.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-list-box-outline"></i>
        <div>My Orders</div>
      </a>
    </li>

</aside>
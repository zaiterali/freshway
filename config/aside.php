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
      <a href="index.php" class="menu-link ">
        <i class="menu-icon tf-icons mdi mdi-home-outline "></i>
        <div>Dashboard</div>
      </a>
    </li>

    <?php
    if ($roleId == 1 || $roleId == 2) { ?>

      <!-- DIVIDER  -->
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Orders & Sales</span>
      </li>
      <!-- ORDERS  -->

      <li class="menu-item  <?php echo ($pageName == "New Order") ? 'active' : '' ?>">
        <a href="neworder.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-plus"></i>
          <div>New Order</div>
        </a>
      </li>
      <li class="menu-item  <?php echo ($pageName == "Orders List") ? 'active' : '' ?>">
        <a href="orders.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-list-box-outline"></i>
          <div>Orders</div>
        </a>
      </li>

    <?php
    }
    ?>



    <?php
    if ($roleId == 1) { ?>
      <!-- DIVIDER  -->
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Menu</span>
      </li>
      <!-- ITEMS  -->

      <li class="menu-item <?php echo ($pageName == "categories") ? 'active' : '' ?>">
        <a href="category.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-menu"></i>
          <div>Categories</div>
        </a>
      </li>
      <li class="menu-item <?php echo ($pageName == "items") ? 'active' : '' ?>">
        <a href="items.php" class="menu-link ">
          <i class="menu-icon tf-icons mdi mdi-french-fries"></i>
          <div>Selling Items</div>
        </a>
      </li>
      <li class="menu-item <?php echo ($pageName == "purchase items") ? 'active' : '' ?>">
        <a href="purchaseitems.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-package-variant"></i>
          <div>Purchase Items</div>
        </a>
      </li>
    <?php
    }
    ?>

    <?php
    if ($roleId == 1 || $roleId == 3) { ?>

      <!-- DIVIDER  -->
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Accounting</span>
      </li>
      <li class="menu-item">
        <a href="reports.php" target="_blank" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-chart-line"></i>
          <div>Reports</div>
        </a>
      </li>
      <li class="menu-item <?php echo ($pageName == "Purchases List") ? 'active' : '' ?>">
        <a href="purchases.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-currency-usd"></i>
          <div>Purchases</div>
        </a>
      </li>
      <li class="menu-item <?php echo ($pageName == "Add Purchase") ? 'active' : '' ?>">
        <a href="addpurchase.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-cart-plus"></i>
          <div>Add Purchase</div>
        </a>
      </li>

    <?php
    }
    ?>

    <?php
    if ($roleId == 1) { ?>

      <!-- DIVIDER  -->
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Administrator</span>
      </li>
      <!-- ORDERS  -->

      <li class="menu-item  <?php echo ($pageName == "Suppliers") ? 'active' : '' ?>">
        <a href="suppliers.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-truck-outline"></i>
          <div>Suppliers</div>
        </a>
      </li>
      <li class="menu-item <?php echo ($pageName == "Clients") ? 'active' : '' ?>">
        <a href="client.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-store-outline"></i>
          <div>Clients</div>
        </a>
      </li>
      <li class="menu-item <?php echo ($pageName == "Users") ? 'active' : '' ?>">
        <a href="user.php" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
          <div>Users</div>
        </a>
      </li>

    <?php
    }
    ?>





</aside>
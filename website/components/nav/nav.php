<div class="container">
  <nav class="navbar">
    <div class="nav-elements">
      <div class="logo">
        <a href="./index.php">
          <img src="./images/logo-no-tag-svg.svg" class="light-logo" alt="">
          <img src="./images/Logo-Dark-nav.svg" alt="" class="dark-logo">
        </a>
      </div>
      <div class="navbar-list">
        <div class="icon"><a href="./index.php"><i class='bx bx-home-alt '></i>Home</a></div>
        <div class="icon"><a href="./shops.php"><i class='bx bx-store'></i>Shops</a></div>
        <div class="icon"><a href="./products.php"><i class='bx bx-package'></i>Products</a></div>
        <div class="icon"><a href="./aboutus.php"><i class='bx bx-info-circle'></i>About</a></div>
        <div class="close nav-toggle">
          <i class='bx bx-x'></i>
        </div>
        <div class="icons">
          <div class="icon search-icon-nav" onclick="openSearch()"><a href="#"><i class='bx bx-search-alt'></i>Search</a></div>
          <div class="icon"><a href="./login.php?loginpoint=mainpage">
              <?php if (isset($_SESSION['USER_ID'])) {
                //$count = count($_SESSION['cart']);
                echo "<a href='manageaccount.php'> <i class='bx bx-user'></i>" . $_SESSION['USER_NAME'] . "</a>";
                echo "<a href='./logout.php'><i class='bx bx-log-out-circle'></i></a></a>";
              } else {
                echo " <i class='bx bx-user'></i>Account";
              }
              ?>
            </a>
          </div>
          <div class="icon cart"><a href="cart.php"><i class='bx bx-cart'></i>Cart
              <?php
              if (isset($_SESSION['USER_ID'])) {
                $uid = $_SESSION['USER_ID'];
                include('./connection.php');
                $sql_query = "SELECT COUNT(FK2_PRODUCT_ID) AS NUMBER_OF_ROWS FROM CART_PRODUCT WHERE ADDED_BY = '$uid'";

                $stmt = oci_parse($conn, $sql_query);

                oci_define_by_name($stmt, 'NUMBER_OF_ROWS', $number_of_rows);

                oci_execute($stmt);

                oci_fetch($stmt);


                echo "<span>" . $number_of_rows . "</span>";
              } else {
                if (isset($_SESSION['cart'])) {
                  $count = count($_SESSION['cart']);
                  echo "<span>$count</span>";
                } else {
                  echo "<span>" . 0 . "</span>";
                }
              }




              ?></a>
          </div>
        </div>
      </div>
      <div class="hamburger nav-toggle">
        <i class='bx bx-menu'></i>
      </div>
    </div>
    <div class="search-bar">
      <form action="" class="search-form">
        <input type="search" placeholder="Search" />
        <button type="submit"><i class='bx bx-search-alt'></i></button>
      </form>
    </div>
  </nav>
</div>
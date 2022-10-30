<section id="traderpage">
  <div class="traderpage-elements">
    <div class="traderpage-left">
      <div class="traderpage-sidenav">
        <div class="traderpage-sidenav-elements">
          <div class="traderpage-sidenav-element">
            <i class='bx bx-user'></i>
            <span><?php echo $_SESSION['TRADER_NAME']; ?></span>
          </div>
          <div class="traderpage-sidenav-element">
            <i class='bx bxs-user-detail'></i>
            <a href="./trader.php?option=edit_details"><span>Edit Details</span></a>
          </div>
          <div class="traderpage-sidenav-element">
            <i class='bx bx-package'></i>
            <a href="./trader.php?option=manage_products"><span>Manage Products</span></a>
          </div>
          <div class="traderpage-sidenav-element">
            <i class='bx bx-basket'></i>
            <a href="./trader.php?option=manage_order"> <span>View Orders</span></a>
          </div>
          <div class="traderpage-sidenav-element">
            <i class='bx bx-star'></i>
            <a href="./trader.php?option=view_reviews"> <span>View Reviews</span></a>
          </div>
          <a href='./logout.php'>
            <div class="traderpage-sidenav-element">
              <i class='bx bx-log-out'></i>
              <span>Logout</span>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="traderpage-right">
      <?PHP
      $option = '';
      if (isset($_GET['option'])) {
        $option = $_GET['option'];
      }
      if ($option == "edit_details") {

        include "./components/traderPage/components/editdetails.php";
      } else if ($option == "manage_products") {

        include "./components/traderPage/components/manageproducts.php";
      } else if ($option == "manage_order") {

        include "./components/traderPage/components/manageorders.php";
      } else if ($option == "add_product") {

        include "./components/traderPage/components/addproducts.php";
      } else if ($option == "edit_product") {

        include "./components/traderPage/components/editproducts.php";
      } else if ($option == "delete_product") {

        include "./components/traderPage/components/deleteproducts.php";
      } else if ($option == "view_reviews") {
        include "./components/traderPage/components/viewreviews.php";
      }
      ?>

    </div>
  </div>
</section>
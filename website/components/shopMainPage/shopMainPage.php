<?php
include('./function.php');
?>

<?php
if (isset($_POST['add'])) {
  $pid = $_POST['product_id'];
  $qty = $_POST['quantity'];
  //WHEN USER LOGGED IN
  if (isset($_SESSION['USER_ID'])) {
    $uid = $_SESSION['USER_ID'];

    manageUserCart($uid, $qty, $pid);
  } else {
    $_SESSION['cart'][$pid]['qty'] = $qty;
    echo '<script>alert("Product added to the cart successfully ...!")</script>';
  }
  $totalCart = count(getUserFullCart());
  $arr = array('totalCartProduct' => $totalCart);
}
?>
<?php
if (isset($_POST['wishlist'])) {
  $pid = $_POST['product_id'];
  //WHEN USER LOGGED IN
  if (isset($_SESSION['USER_ID'])) {
    $uid = $_SESSION['USER_ID'];
    manageUserWishlist($uid, $pid);
  } else {
    echo '<script>alert("Adding to wishlist requires logging in!")</script>';
    echo '<script>window.location="./login.php?loginpoint=customerLogin"</script>';
  }
}
?>
<section id="shopmainpage">
  <div class="container">
    <div class="shopmainpage-elements">
      <div class="shop-banner">
        <?php
        $sid = $_GET['id'];
        include('./connection.php');
        $sql = oci_parse($conn, "SELECT S.SHOP_NAME,S.BANNER_IMAGE,T.TRADER_NAME FROM SHOP S,TRADER T WHERE T.USER_ID=S.FK1_USER_ID AND S.SHOP_ID = '$sid'");
        oci_execute($sql);
        while ($row = oci_fetch_array($sql)) {

        ?>
          <img src="./images/shop/<?php echo $row['BANNER_IMAGE'];?>" alt="">
          <div class="shop-banner-elements">
            <div class="shop-banner-elements-logo">
              <i class='bx bxs-store'></i>
            </div>
            <div class="shop-banner-inner-elements">
              <div class="shop-banner-elements-left">
                <div class="shop-banner-elements-name">
                  <h6><?php echo $row['SHOP_NAME'] ?></h6>
                </div>
                <h5><i class='bx bxs-badge-dollar'></i><?php echo $row['TRADER_NAME'] ?></h5>
              </div>
              <div class="shop-banner-elements-right">
                <input type="search">
                <i class='bx bx-search-alt-2'></i>
              </div>
            </div>
          </div>
      </div>
    <?php } ?>
    <div class="featured-products">
      <?php
      include('./connection.php');
      $product = oci_parse($conn, "SELECT P.PRODUCT_ID,P.MINIMUM_ORDER,P.MAXIMUM_ORDER,P.PRODUCT_STOCK,P.PRODUCT_IMAGE,P.PRODUCT_NAME,T.PRODUCT_TYPE_NAME,P.PRODUCT_PRICE FROM PRODUCT P,PRODUCT_TYPE T WHERE P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID AND P.FK1_SHOP_ID='$sid'");
      oci_execute($product);
      while ($row = oci_fetch_array($product)) {
        $pid = $row['PRODUCT_ID'];

      ?>
        <?php echo "<a href='./product.php?id=$pid'>"; ?>
        <div class="featured-product">
          <img src="./images/products/<?php echo $row['PRODUCT_IMAGE']; ?>" alt="">
          <div class="text">
            <div class="title-category">
              <h1 class="title"><?php echo $row['PRODUCT_NAME']; ?></h1>
              <h5 class="category"><?php echo $row['PRODUCT_TYPE_NAME']; ?></h5>
            </div>
            <div class="review-star">
              <?php
              include('connection.php');
              $productid = $row['PRODUCT_ID'];
              $review = oci_parse($conn, "SELECT AVG(RATING) FROM REVIEW WHERE FK1_PRODUCT_ID='$productid'");
              oci_execute($review);
              while ($star = oci_fetch_assoc($review)) {
                $avrating = $star['AVG(RATING)'];
                $avrating = $star['AVG(RATING)'];
                if ($avrating >= 1.5 && $avrating < 2) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star-half'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                } elseif ($avrating == 1) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                } elseif ($avrating == 2) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                } elseif ($avrating == 3) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                } elseif ($avrating == 4) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                } elseif ($avrating >= 2.5 && $avrating < 3) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star-half'></i>";
                  echo "<i class='bx bx-star'></i>";
                  echo "<i class='bx bx-star'></i>";
                } elseif ($avrating >= 3.5 && $avrating < 4) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star-half'></i>";
                  echo "<i class='bx bx-star'></i>";
                } elseif ($avrating >= 4.5 && $avrating < 5) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star-half'></i>";
                } elseif ($avrating == 5) {
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                  echo "<i class='bx bxs-star'></i>";
                } else {
                  echo "Not Rated Yet";
                }
              } ?>
              <span class="review-numbers">(<?php echo round($avrating, 2); ?>)</span>
            </div>
            <div class='price-btn'>
              <div class='prices'>
                <h1 class='price new-price'>
                  <?PHP echo "£" . number_format($row['PRODUCT_PRICE'], 2); ?>
                </h1>
              </div>
              <form method="post" action=''>
                <input type="hidden" name="product_id" value="<?php echo oci_result($product, 'PRODUCT_ID'); ?>">
                <input type="hidden" name="hidden_name" value="<?php echo oci_result($product, 'PRODUCT_NAME'); ?>">
                <input type="hidden" name="hidden_price" value="<?php echo oci_result($product, 'PRODUCT_PRICE'); ?>">
                <input type="hidden" name="hidden_image" value="<?php echo oci_result($product, 'PRODUCT_IMAGE'); ?>">
                <input type="hidden" name="quantity" value="<?php echo oci_result($product, 'MINIMUM_ORDER'); ?>">
                <input type="hidden" name="stock" value="<?php echo oci_result($product, 'PRODUCT_STOCK'); ?>">
                <?php
                if ($row['PRODUCT_STOCK'] != 0) {
                  echo '<button class="btn" type="submit" name="add"><i class="bx bxs-cart-add"></i></button>';
                } else {
                  echo '<i class="bx bx-cart"></i>';
                }
                ?>

                <button class="btn" type="submit" name="wishlist"><i class="bx bxs-heart-circle"></i></button>
              </form>
            </div>
          </div>
        </div>
        </a>
      <?php } ?>
    </div>
    </div>
  </div>
</section>
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
include('connection.php');
$sql = oci_parse($conn, "SELECT * FROM(SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME HAVING AVG(R.RATING)>=4 ORDER BY AVG(R.RATING) ASC) WHERE ROWNUM<=4");
oci_execute($sql);
?>

<section id="featured">
  <div class="container">
    <div class="featured">
      <div class="text-heading">
        <h1 class="heading">Featured Product</h1>
      </div>
      <div class="featured-products">
        <?PHP
        while ($row = oci_fetch_array($sql, OCI_ASSOC + OCI_RETURN_NULLS)) {
          //print_r($row);
        ?>
          <?php
          $id = $row['PRODUCT_ID'];
          echo "<a href='./product.php?id=$id'>" ?>
          <div class="featured-product">
            <img src="./images/products/<?php echo $row['PRODUCT_IMAGE']; ?>" alt="">
            <div class="text">
              <div class="title-category">
                <h1 class="title"><?php echo $row['PRODUCT_NAME']; ?></h1>
                <h5 class="category"><?php echo $row['PRODUCT_TYPE_NAME'];
                                      ?></h5>
              </div>
              <div class="review-star">
                <?php
                include('connection.php');
                $review = oci_parse($conn, "SELECT AVG(RATING) FROM REVIEW WHERE FK1_PRODUCT_ID='$id'");
                oci_execute($review);
                while ($star = oci_fetch_assoc($review)) {
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
              <form method="post" action="">
                <div class="price-btn">
                  <h1 class="price"><?php echo "£" . number_format($row['PRODUCT_PRICE'], 2); ?></h1>
                  <input type="hidden" name="product_id" value="<?php echo oci_result($sql, 'PRODUCT_ID'); ?>">
                  <input type="hidden" name="hidden_name" value="<?php echo oci_result($sql, 'PRODUCT_NAME'); ?>">
                  <input type="hidden" name="hidden_price" value="<?php echo oci_result($sql, 'PRODUCT_PRICE'); ?>">
                  <input type="hidden" name="hidden_image" value="<?php echo oci_result($sql, 'PRODUCT_IMAGE'); ?>">
                  <input type="hidden" name="quantity" value="<?php echo oci_result($sql, 'MINIMUM_ORDER'); ?>">
                  <input type="hidden" name="stock" value="<?php echo oci_result($sql, 'PRODUCT_STOCK'); ?>">
                  <?php
                  if ($row['PRODUCT_STOCK'] != 0) {
                    echo '<button class="btn" type="submit" name="add"><i class="bx bxs-cart-add"></i></button>';
                  } else {
                    echo '<i class="bx bx-cart"></i>';
                  }
                  ?>



                </div>
              </form>
            </div>
          </div>
          </a>
        <?PHP } ?>

      </div>
      <div class="view-more">
        <a href="./products.php?ratescore=4"><button class="view-more-btn">View More</button></a>
      </div>
    </div>
  </div>
</section>
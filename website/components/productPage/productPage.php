<?php
include('./function.php');
?>
<?php
if (isset($_POST['review_submit'])) {
  //prx($_POST);
  $rating_number = $_POST['rating_number'];
  $review_area = $_POST['review_area'];
  $added_on = date('j:M:y H:i:s');
  $revprod = $_GET['id'];
  $revuser = $_SESSION['USER_ID'];

  include('./connection.php');
  $checkreview = oci_parse($conn, "SELECT * FROM REVIEW WHERE FK1_PRODUCT_ID='$revprod' AND FK2_USER_ID='$revuser'");
  oci_execute($checkreview);
  $verify = oci_fetch_assoc($checkreview);
  if ($verify > 0) {
    $reviewid = $verify['REVIEW_ID'];
    $revupdate = oci_parse($conn, "UPDATE REVIEW SET REVIEW_DESCRIPTION='$review_area', RATING='$rating_number',REVIEW_DATE='' WHERE REVIEW_ID='$reviewid'");
    oci_execute($revupdate);
    echo "<script>alert('Review Updated Successfully')</script>";
  } else {
    $revinsert = oci_parse($conn, "INSERT INTO REVIEW(FK2_USER_ID,REVIEW_DESCRIPTION,FK1_PRODUCT_ID,RATING,STATUS,REVIEW_DATE) 
  values('" . $_SESSION['USER_ID'] . "','$review_area','$revprod','$rating_number',1,to_date('$added_on', 
  'DD:MON:YY HH24:MI:SS'))");
    oci_execute($revinsert);
    echo "<script>alert('Review Posted Successfully')</script>";
  }
}

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
    //echo '<script>'.'window.location="./product.php"'.'?id="$pid"'.'</script>';
  }
  $totalCart = count(getUserFullCart());
  $arr = array('totalCartProduct' => $totalCart);
}
?>
<?php
$productid = $_GET['id'];
include('./connection.php');
$sql = oci_parse($conn, "SELECT P.PRODUCT_ID,S.SHOP_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.ALLERGY_INFORMATION,P.PRODUCT_STOCK,P.PRODUCT_PRICE,P.OLD_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME,S.SHOP_NAME,P.LONG_DESC FROM PRODUCT P,PRODUCT_TYPE T,SHOP S WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND P.FK1_SHOP_ID=S.SHOP_ID AND P.PRODUCT_ID='$productid'");
oci_execute($sql);
while ($row = oci_fetch_array($sql, OCI_ASSOC)) {
?>


  <section id="product-page">
    <div class="container">
      <div class="product-section-first-section">
        <div class="product-page-elements">
          <div class="product-left">
            <img src='./images/products/<?php echo oci_result($sql, 'PRODUCT_IMAGE') ?>' alt='<?php echo oci_result($sql, 'PRODUCT_IMAGE') ?>'>
          </div>
          <div class="product-right">
            <div class="product-page-title">
              <h1><?php echo $row['PRODUCT_NAME']; ?></h1>
            </div>
            <div class="product-page-product-category">
              <span><?php $type = $row['PRODUCT_TYPE_NAME'];
                    echo $type; ?></span>
            </div>
            <div class="product-page-rating">
              <?php
              include('connection.php');
              $review = oci_parse($conn, "SELECT AVG(RATING) FROM REVIEW WHERE FK1_PRODUCT_ID='$productid'");
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
            <div class="product-page-price">
              <div class="product-page-actual-price">
                M.R.P: £<?php echo number_format($row['OLD_PRICE'], 2); ?>
              </div>
              <div class="product-page-discounted-price">
                <?php echo "PRICE: £" . number_format($row['PRODUCT_PRICE'], 2); ?>
              </div>
              <div class="product-page-saved-cost">
                You Save: £<?php echo number_format(($row['OLD_PRICE'] - $row['PRODUCT_PRICE']), 2); ?>
              </div>
            </div>
            <div class="product-page-availability-status">
              <?php //compare stock from table 
              $stock = $row['PRODUCT_STOCK'];
              if ($stock > 0 OR $stock='') echo '<div class="product-page-in-stock">In Stock</div>';
              else echo '<div class="product-page-out-stock">Out of Stock</div>'
              ?>
            </div>
            <div class="product-page-sold-by">
              <a href="shop.php?id=<?php echo $row['SHOP_ID']; ?>"><i class='bx bxs-store'></i><?php echo $row['SHOP_NAME']; ?></a>
            </div>
            <div class="product-page-about-this-item">
              <h5>About this item</h5>
              <p>
                <?php echo $row['PRODUCT_DETAIL'] . "<span id='dots'>...</span>" . " <span id='more'><br>" . $row['LONG_DESC'] . "</span>"; ?> <br>
                <a onclick="showDetailFunction()" id="myBtn">View More</a>
              </p>
            </div>
            <div class="product-page-buttons">
  
              <form method="post" action="">
                <input type="hidden" name="product_id" value="<?php echo oci_result($sql, 'PRODUCT_ID'); ?>">
                <input type="hidden" name="hidden_name" value="<?php echo oci_result($sql, 'PRODUCT_NAME'); ?>">
                <input type="hidden" name="hidden_price" value="<?php echo oci_result($sql, 'PRODUCT_PRICE'); ?>">
                <input type="hidden" name="hidden_image" value="<?php echo oci_result($sql, 'PRODUCT_IMAGE'); ?>">
                <input type="hidden" name="quantity" value="<?php echo oci_result($sql, 'MINIMUM_ORDER'); ?>">
                <div class="product-page-add-to-cart-button">
                  <?php
                  if ($row['PRODUCT_STOCK'] != 0) {
                    echo '<button class="btn" type="submit" name="add">Add to Cart</button>';
                  } else {
                    echo '';
                  }
                  ?>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="product-section-second-section">
        <div class="product-section-second-section-title">
          <h4>Products That Are Similar</h4>
        </div>
        <div class="product-section-second-section-related-products">
          <?php
          $ns = $_GET['id'];
          $sql5 = oci_parse($conn, "SELECT P.PRODUCT_ID,P.PRODUCT_NAME,P.ALLERGY_INFORMATION,P.PRODUCT_STOCK,P.PRODUCT_PRICE,P.OLD_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME,S.SHOP_NAME,P.LONG_DESC FROM PRODUCT P,PRODUCT_TYPE T,SHOP S WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND P.FK1_SHOP_ID=S.SHOP_ID AND T.PRODUCT_TYPE_NAME='$type' AND P.PRODUCT_ID!='$ns' AND ROWNUM<=4");
          oci_execute($sql5);
          while ($row5 = oci_fetch_array($sql5, OCI_ASSOC)) {
          ?>
            <?php
            $id = $row5['PRODUCT_ID'];
            echo "<a href='./product.php?id=$id'>"
            ?>
            <div class="product-section-second-section-related-product">
              <img src='./images/products/<?php echo oci_result($sql5, 'PRODUCT_IMAGE') ?>' alt='<?php echo oci_result($sql5, 'PRODUCT_IMAGE') ?>'>
              <h5><?php echo $row5['PRODUCT_NAME']; ?></h5>
              <span><?php echo "£" . $row5['PRODUCT_PRICE']; ?></span></a>
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="product-section-third-section">
        <div class="product-section-third-section-title">
          <h3>Product Description</h3>
        </div>
        <div class="product-section-third-section-product-description">
          <p><?php echo $row['LONG_DESC']; ?></p>
        </div>
        <div class="product-section-third-section-title important-information">
          <h3 class="important-text">Important information</h3>
          <h2>Allergy Information:</h2>
        </div>
        <div class="product-section-third-section-product-description">
          <p><?php echo $row['ALLERGY_INFORMATION']; ?></p>
        <?php } ?>

        </div>
        <div class="features">
          <div class="feature">
            <div class="feature-icon">
              <i class='bx bx-lock-alt'></i>
            </div>
            <div class="title">Secure Shopping</div>
            <div class="sub-title">We are committed to protecting the security of your information</div>
          </div>
          <div class="feature middle-featured">
            <div class="feature-icon">
              <i class='bx bx-package'></i>
            </div>
            <div class="title">Quality Checked</div>
            <div class="sub-title">We are providing top quality genuine products and service.</div>
          </div>
          <div class="feature">
            <div class="feature-icon">
              <i class='bx bx-store-alt'></i>
            </div>
            <div class="title">Easy Return</div>
            <div class="sub-title">Easy returns on our products, Returns are free and easy!</div>
          </div>
        </div>
        <div class="product-section-reviews">
          <div class="product-section-reviews-title">
            <h5>Reviews</h5>
          </div>
          <?php
          include('./connection.php');
          $rev = oci_parse($conn, "SELECT R.REVIEW_DESCRIPTION,R.RATING,C.CUSTOMER_NAME FROM REVIEW R,CUSTOMER C WHERE R.FK2_USER_ID=C.USER_ID AND R.FK1_PRODUCT_ID='$productid' AND R.STATUS=1");
          oci_execute($rev);

          while ($row = oci_fetch_array($rev)) {
            $rating = $row['RATING'];

          ?>
            <div class="product-section-review">
              <div class="product-section-review-first-section">
                <div class="product-section-reviews-icon">
                  <i class='bx bx-user-circle'></i>
                </div>
                <div class="product-section-review-first-second-section">
                  <div class="product-section-reviews-username">
                    <h6><?php echo $row['CUSTOMER_NAME']; ?></h6>
                    <div class="product-section-reviews-stars">
                      <div class="product-page-rating">

                        <?php
                        if ($rating == 1) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                        } elseif ($rating == 2) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                        } elseif ($rating == 3) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                        } elseif ($rating == 4) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                        } elseif ($rating == 5) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                        } elseif ($rating == 1.5) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star-half'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                        } elseif ($rating == 2.5) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star-half'></i>";
                          echo "<i class='bx bx-star'></i>";
                          echo "<i class='bx bx-star'></i>";
                        } elseif ($rating == 3.5) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star-half'></i>";
                          echo "<i class='bx bx-star'></i>";
                        } elseif ($rating == 4.5) {
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star'></i>";
                          echo "<i class='bx bxs-star-half'></i>";
                        } else {
                          echo "Not Rated";
                        }
                        ?>
                        <span class="review-numbers"></span>
                      </div>
                    </div>
                  </div>
                  <div class="product-section-reviews-review-text">
                    <p><?php echo $row['REVIEW_DESCRIPTION']; ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
          <div class="product-review">
            <h3 class='rateheading'>Post Your Review</h3>

            <form action="" method="post">
              <?php if (isset($_SESSION['USER_ID'])) {
                $activeproduct = $_GET['id'];
                $activeuser = $_SESSION['USER_ID'];
                $checkorders = oci_parse($conn, "SELECT OP.FK1_PRODUCT_ID,O.CUSTOMER_ID FROM ORDERS_PRODUCT OP,ORDERS O WHERE OP.ORDER_ID=O.ORDER_ID AND OP.FK1_PRODUCT_ID='$activeproduct' AND O.CUSTOMER_ID='$activeuser'");
                oci_execute($checkorders);
                $verifyorder = oci_fetch($checkorders);
                if ($verifyorder > 0) {

              ?>
                  <select name="rating_number" class="rate_area" required>
                    <option value="">Select Rating</option>
                    <option value='1'>&#9733;</option>
                    <option value='2'>&#9733; &#9733;</option>
                    <option value='3'>&#9733; &#9733; &#9733;</option>
                    <option value='4'>&#9733; &#9733; &#9733; &#9733;</option>
                    <option value='5'> &#9733; &#9733; &#9733; &#9733;&#9733;</option>
                  </select>
                  </br>
                  <textarea name="review_area" class="review_area" cols="50" rows="5" placeholder="Enter your Review here....." required></textarea>
                  <div>
                    <button type="submit" class="subreview" name="review_submit"><i class='bx bx-comment-dots'></i></button>
                  </div>
              <?php } else {
                  echo "<h4 align='center'>Please purchase this product in order to post a review<h4>";
                }
              } else {
                echo "<h4 align='center'>Please <a href='login.php?loginpoint=customerLogin'>login</a> to rate and review this product</h4>";
              }
              ?>
            </form>
          </div>
        </div>
      </div>
  </section>
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


<?php
include('connection.php');
$sortby = '';
$orderby = '';
$search = '';
$type = '';
if (isset($_GET['ratescore'])) {
  $ratescore = $_GET['ratescore'];
  $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME HAVING AVG(R.RATING)>=4");
} else if (isset($_GET['dealprice'])) {
  $dealprice = $_GET['dealprice'];
  $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T  WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND P.PRODUCT_PRICE<'$dealprice' ORDER BY P.PRODUCT_PRICE ASC");
} else if (isset($_GET['query'])) {
  $query = strtoupper($_GET['query']);
  $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND UPPER(PRODUCT_NAME) LIKE '%$query%'");
} else {
  $sql = oci_parse($conn, 'SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND P.STATUS=1 ORDER BY P.PRODUCT_NAME ASC');
}
if (isset($_GET['filterresult'])) {
  $sortby = $_GET['sort-by'];
  $orderby = $_GET['order-by'];
  $search = $_GET['search'];
  if (isset($_GET['type'])) {
    $type = $_GET['type'];
  }
  if ($type)
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND T.PRODUCT_TYPE_NAME='$type'");
  else if ($sortby == 'Rating' && $orderby == '1star')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME HAVING AVG(R.RATING)>=1 ORDER BY AVG(R.RATING) ASC");
  else if ($sortby == 'Rating' && $orderby == '2star')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME HAVING AVG(R.RATING)>=2 ORDER BY AVG(R.RATING) ASC");
  else if ($sortby == 'Rating' && $orderby == '3star')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME HAVING AVG(R.RATING)>=3 ORDER BY AVG(R.RATING) ASC");
  else if ($sortby == 'Rating' && $orderby == '4star')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME HAVING AVG(R.RATING)>=4 ORDER BY AVG(R.RATING) ASC");
  else if ($sortby == 'Rating' && $orderby == '5star')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME HAVING AVG(R.RATING)=5 ORDER BY AVG(R.RATING) ASC");
  else if ($sortby == 'Rating' && $orderby == 'DESC')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME ORDER BY AVG(R.RATING) DESC");
  else if ($sortby == 'Rating' && $orderby == 'ASC')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,AVG(R.RATING),T.PRODUCT_TYPE_NAME FROM REVIEW R INNER JOIN PRODUCT P ON P.PRODUCT_ID = R.FK1_PRODUCT_ID INNER JOIN PRODUCT_TYPE T ON P.FK2_PRODUCT_TYPE_ID=T.PRODUCT_TYPE_ID GROUP BY P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME ORDER BY AVG(R.RATING) ASC");

  else if ($sortby == 'Name' && $orderby == 'ASC' && $search)
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND UPPER(P.PRODUCT_NAME) LIKE UPPER('%$search%') ORDER BY P.PRODUCT_NAME ASC");

  else if ($sortby == 'Name' && $orderby == 'DESC')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND UPPER(P.PRODUCT_NAME) LIKE UPPER('%$search%') ORDER BY P.PRODUCT_NAME DESC");

  else if ($sortby == 'Price' && $orderby == 'ASC')
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND UPPER(P.PRODUCT_NAME) LIKE UPPER('%$search%') ORDER BY P.PRODUCT_PRICE ASC");

  else if ($sortby == 'Price' && $orderby == 'DESC')
    $sql = oci_parse($conn, "SELECT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND UPPER(P.PRODUCT_NAME) LIKE UPPER('%$search%') ORDER BY P.PRODUCT_PRICE DESC");
  else if ($sortby == 'Name' && $orderby == 'ASC' && $search && $type)
    $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND UPPER(P.PRODUCT_NAME) LIKE UPPER('%$search%') AND T.PRODUCT_TYPE_NAME='$type' ORDER BY P.PRODUCT_NAME ASC");
  else {
    $sql = oci_parse($conn, 'SELECT DISTINCT P.PRODUCT_STOCK,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND P.STATUS=1 ORDER BY P.PRODUCT_NAME ASC');
  }
}
oci_execute($sql);
?>
<section id="products-page">
  <div class='container'>
    <div class="products-page">
      <div class="products-filter">
        <div class="filter-title">
          <form method='get' action=''>
            <h3>Category</h3>
            <div class="close-filter">
              <i class='bx bx-x'></i>
            </div>
        </div>
        <?PHP
        $sql1 = oci_parse($conn, 'SELECT PRODUCT_TYPE_NAME FROM PRODUCT_TYPE');
        oci_execute($sql1);
        while ($row = oci_fetch_array($sql1, OCI_ASSOC)) {

        ?>
          <div class="filters">
            <input type="radio" id="cake" name="type" value="<?php echo $row['PRODUCT_TYPE_NAME'];
                                                              if (isset($_GET['type']) && $_GET['type'] == $row['PRODUCT_TYPE_NAME']) ?>">
            <label for="masu"><?php echo $row['PRODUCT_TYPE_NAME']; ?></label><br>
          </div>
        <?php } ?>

      </div>
      <div class="all-products">
        <div class="sorting-filter">
          <div class="products-filter-mobile-icon">
            <i class='bx bx-filter-alt'></i>
            <div class="filters-text">
              Filters
            </div>
          </div>
          <div class="sorting-filter-middle">
            <div class="filters sort-by-first">
              <label for="sort-by">Sort By:</label>
              <select name="sort-by" id="sord-by">
                <option value="Name" <?php if ($sortby == 'Name') echo ' selected="selected"'; ?>>Name</option>
                <option value="Price" <?php if ($sortby == 'Price') echo ' selected="selected"'; ?>>Price</option>
                <option value="Rating" <?php if ($sortby == 'Rating') echo ' selected="selected"'; ?>>Rating</option>
              </select>
            </div>
            <div class="filters order-by-first">
              <label for="order-by">Order By:</label>
              <select name="order-by" id="order-by">
                <option value="ASC" <?php if ($orderby == 'ASC') echo ' selected="selected"'; ?>>ASCENDING</option>
                <option value="DESC" <?php if ($orderby == 'DESC') echo ' selected="selected"'; ?>>DESCENDING</option>
                <option value="1star" <?php if ($orderby == '1star') echo ' selected="selected"'; ?>>&#x2605;</option>
                <option value="2star" <?php if ($orderby == '2star') echo ' selected="selected"'; ?>>&#x2605;&#x2605;</option>
                <option value="3star" <?php if ($orderby == '3star') echo ' selected="selected"'; ?>>&#x2605;&#x2605;&#x2605;</option>
                <option value="4star" <?php if ($orderby == '4star') echo ' selected="selected"'; ?>>&#x2605;&#x2605;&#x2605;&#x2605;</option>
                <option value="5star" <?php if ($orderby == '5star') echo ' selected="selected"'; ?>>&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</option>
              </select>
            </div>
            <div class="filters search-by-first">
              <label for="search"><i class='bx bx-search-alt'></i></label>
              <input type="text" name="search" value="<?php if ($search) echo $search; ?>">
            </div>
          </div>
          <button name='filterresult'>Apply</button>
          </form>
        </div>

        <div class='products'>
          <div class='featured-products'>
            <?PHP
            while ($row = oci_fetch_array($sql, OCI_ASSOC + OCI_RETURN_NULLS)) {
              //print_r($row);
            ?>
              <?php
              $id = $row['PRODUCT_ID'];
              echo "<a href='./product.php?id=$id'>" ?>
              <div class='featured-product'>
                <img src='./images/products/<?php echo oci_result($sql, 'PRODUCT_IMAGE') ?>' alt='<?php echo oci_result($sql, 'PRODUCT_IMAGE') ?>'>
                <!---<img src='./images/trader-2.png'>-->
                <div class='text'>
                  <div class='title-category'>
                    <h1 class='title'>
                      <?PHP echo oci_result($sql, 'PRODUCT_NAME'); ?>
                    </h1>
                    <h5 class='category'>
                      <?PHP echo oci_result($sql, 'PRODUCT_TYPE_NAME'); ?>
                    </h5>
                  </div>
                  <div class='review-star'>
                    <?php
                    include('connection.php');
                    $pid = $row['PRODUCT_ID'];
                    $review = oci_parse($conn, "SELECT AVG(RATING) FROM REVIEW WHERE FK1_PRODUCT_ID='$pid'");
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

                      <button class="btn" type="submit" name="wishlist"><i class="bx bxs-heart-circle"></i></button>
                    </form>
                  </div>
                </div>
              </div>
              </a>
            <?PHP } ?>

          </div>
        </div>
      </div>
    </div>
</section>
<section id="deals">
  <div class="container">
    <div class="featured">
      <div class="text-heading">
        <h1 class="heading">Deals of the Day</h1>
      </div>
      <?php
      include('connection.php');
      $deals = oci_parse($conn, 'SELECT DISTINCT P.PRODUCT_STOCK,P.OLD_PRICE,P.PRODUCT_ID,P.MINIMUM_ORDER,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_IMAGE,T.PRODUCT_TYPE_NAME FROM PRODUCT P,PRODUCT_TYPE T WHERE T.PRODUCT_TYPE_ID=P.FK2_PRODUCT_TYPE_ID AND P.STATUS=1 AND P.PRODUCT_STOCK>0 AND P.PRODUCT_PRICE<2.99 AND ROWNUM<5');
      oci_execute($deals);
      ?>
      <div class="featured-products">
        <?PHP
        while ($row = oci_fetch_array($deals, OCI_ASSOC + OCI_RETURN_NULLS)) {
          //print_r($row);
        ?>
          <?php
          $productid = $row['PRODUCT_ID'];
          echo "<a href='./product.php?id=$productid'>" ?>
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
              <form method="post" action="">
                <div class="price-btn">
                  <div class="prices">
                    <h1 class="price old-price">£<?php echo number_format($row['OLD_PRICE'], 2); ?></h1>
                    <h1 class="price new-price">£<?php echo number_format($row['PRODUCT_PRICE'], 2); ?></h1>
                    <input type="hidden" name="product_id" value="<?php echo oci_result($deals, 'PRODUCT_ID'); ?>">
                    <input type="hidden" name="hidden_name" value="<?php echo oci_result($deals, 'PRODUCT_NAME'); ?>">
                    <input type="hidden" name="hidden_price" value="<?php echo oci_result($deals, 'PRODUCT_PRICE'); ?>">
                    <input type="hidden" name="hidden_image" value="<?php echo oci_result($deals, 'PRODUCT_IMAGE'); ?>">
                    <input type="hidden" name="quantity" value="<?php echo oci_result($deals, 'MINIMUM_ORDER'); ?>">
                    <input type="hidden" name="stock" value="<?php echo oci_result($deals, 'PRODUCT_STOCK'); ?>">
                  </div>
                  <button class="btn" name='add'><i class='bx bxs-cart-add'></i></button>
                </div>
              </form>
            </div>
          </div>
          </a>
        <?PHP } ?>

      </div>
      <div class="view-more">
        <a href="./products.php?dealprice=3"> <button class="view-more-btn">View More</button></a>
      </div>
    </div>
  </div>
</section>
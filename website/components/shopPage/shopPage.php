<section id="shop-page">
  <div class="container">
    <div class="shop-page-title">
      <h2>Shops</h2>
    </div>
    <div class="shops">
      <?php
      include('./connection.php');
      $sql = oci_parse($conn, "SELECT * FROM SHOP ORDER BY SHOP_NAME");
      if (isset($_GET['traderid'])) {
        $trader = $_GET['traderid'];
        $sql = oci_parse($conn, "SELECT * FROM SHOP WHERE FK1_USER_ID='$trader' ");
      }
      oci_execute($sql);
      while ($row = oci_fetch_array($sql)) {


      ?>
        <div class="shop">
          <a href="./shop.php?id=<?php echo $row['SHOP_ID']; ?>">
            <img src="./images/shop/<?php echo $row['SHOP_IMAGE']; ?>" alt="">
            <h5><?php echo $row['SHOP_NAME']; ?></h5>
          </a>
        </div>
      <?php } ?>
    </div>
  </div>
</section>
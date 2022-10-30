<div class="traderpage-manageproduct-elements">
  <div class="traderpage-manageproduct-element">

    <div class="traderpage-manageproduct-elements-buttons">
      <a href="./trader.php?option=add_product"> <button>Add Products</button></a>
    </div>
    <h1 class='accheading'>Manage Products</h1>

    <table class="table">
      <thead>
        <th>Product Name</th>
        <th>Product Stock</th>
        <th>Product Rate</th>
        <th>Product Detail</th>
        <th>Shop Name</th>
        <th>Edit</th>
        <th>Delete</th>
      </thead>
      <?php
      $uid = $_SESSION['TRADER_ID'];
      include('./connection.php');
      $sql = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_ID,P.PRODUCT_NAME,P.PRODUCT_PRICE,P.PRODUCT_DETAIL,P.PRODUCT_STOCK,S.SHOP_NAME FROM PRODUCT P,SHOP S, TRADER T WHERE P.FK1_SHOP_ID=S.SHOP_ID AND S.FK1_USER_ID='$uid' ");
      oci_execute($sql);
      while ($row = oci_fetch_array($sql)) {

      ?>
        <tr>
          <td data-label="Product Name"><?php echo $row['PRODUCT_NAME']; ?></td>
          <td data-label="Product Stock"><?php echo $row['PRODUCT_STOCK']; ?></td>
          <td data-label="Product Rate"><?php echo '£' . $row['PRODUCT_PRICE']; ?></td>
          <td data-label="Product Detail"><?php echo $row['PRODUCT_DETAIL']; ?></td>
          <td data-label="Shop Name"><?php echo $row['SHOP_NAME']; ?></td>
          <td data-label="Edit"> <a href="./trader.php?option=edit_product&productid=<?php echo $row['PRODUCT_ID'] ?>"> <i class='bx bx-edit-alt'></i> </a></td>
          <td data-label="Delete"> <a href="./trader.php?option=delete_product&productid=<?php echo $row['PRODUCT_ID'] ?>"> <i class='bx bx-trash'></i></a></td>
        </tr>
      <?php } ?>

    </table>

  </div>
</div>
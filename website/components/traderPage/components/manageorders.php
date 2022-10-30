<div class="traderpage-manageorders-elements">
  <h1 class='accheading'>Orders List</h1>
  <div class="traderpage-manageorders-element">

    <table class='table'>
      <thead>
        <th>Order ID</th>
        <th>Order Product</th>
        <th>Order Quantity</th>
        <th>Order Status</th>
        <th>Payment Status</th>
        <th>Order Total</th>
        <th>Order Date</th>
        <th>Order Under</th>
      </thead>
      <?php
      include('./connection.php');
      $tid = $_SESSION['TRADER_ID'];
      $qry = oci_parse($conn, "SELECT OP.ORDER_PRODUCT_ID,P.PRODUCT_NAME,OP.QUANTITY,OP.UNIT_PRICE,O.ORDER_STATUS,O.PAYMENT_STATUS,O.ORDER_DATE,C.CUSTOMER_NAME FROM ORDERS_PRODUCT OP,ORDERS O,PRODUCT P,SHOP S,TRADER T,CUSTOMER C WHERE OP.FK1_PRODUCT_ID=P.PRODUCT_ID AND S.SHOP_ID=P.FK1_SHOP_ID AND S.FK1_USER_ID=T.USER_ID AND OP.ORDER_ID=O.ORDER_ID AND O.CUSTOMER_ID=C.USER_ID AND T.USER_ID='$tid'");
      oci_execute($qry);
      while ($fetchorder = oci_fetch_assoc($qry)) {
      ?>
        <tr>
          <td><?php echo $fetchorder['ORDER_PRODUCT_ID']; ?></td>
          <td><?php echo $fetchorder['PRODUCT_NAME']; ?></td>
          <td><?php echo $fetchorder['QUANTITY']; ?></td>
          <td data-label="Order Status">
            <?php
            if ($fetchorder['ORDER_STATUS'] == 0) {
              $ostatus = 'Pending';
            } else {
              $ostatus = 'Complete';
            }
            ?>
            <div class="order_status1 order_status_<?php echo $fetchorder['ORDER_STATUS'] ?>">
              <?php echo $ostatus; ?></div>

          </td>
          <td data-label="Payment Status">
            <?php
            if ($fetchorder['PAYMENT_STATUS'] == 0) {
              $pstatus = 'Pending';
            } else {
              $pstatus = 'Complete';
            }
            ?>
            <div class="payment_status1 payment_status_<?php echo $fetchorder['PAYMENT_STATUS'] ?>"><?php echo $pstatus; ?></div>
          </td>
          <td data-label="Order Total"><?php echo '£' . number_format(($fetchorder['UNIT_PRICE'] * $fetchorder['QUANTITY']), 2); ?>
          <td data-label="Order Date"><?php echo $fetchorder['ORDER_DATE']; ?></td>
          <td data-label="Order Under"><?php echo $fetchorder['CUSTOMER_NAME']; ?></td>
        </tr>
      <?php } ?>

    </table>
  </div>
</div>
<?php
session_start();
include('function.php');
include('smtp/PHPMailerAutoload.php');
include('sendmail.php');
$cartArr = getUserFullCart();
?>

<?php
$uid = $_SESSION['USER_ID'];
if (isset($_POST['confirm'])) {
  include('./connection.php');
  $cartArr = getUserFullCart();
  $email = $_POST['eemail'];
  $ename = $_POST['ename'];
  $fintotal = $_SESSION['ptotal'];
  $finqty = $_SESSION['TQUANTITY'];
  $added_on = date('j:M:y H:i:s');
  $colday = $_SESSION['slotday'];
  $coltime = $_SESSION['slottime'];
  $uniqueorder = generate_unique_order_notation();
  $sql = oci_parse($conn, "INSERT INTO ORDERS(ORDER_QUANTITY,CUSTOMER_ID,ORDER_TOTAL,ORDER_STATUS,PAYMENT_STATUS,COLLECTION_DATE,TIME_SLOT,UNIQUE_NOTATION,ORDER_DATE) VALUES('$finqty','$uid','$fintotal',0,0,'$colday','$coltime','$uniqueorder',to_date('$added_on', 
  'DD:MON:YY HH24:MI:SS'))");
  $success = oci_execute($sql);
  if ($success) {
    $pickorder = oci_parse($conn, "SELECT * FROM ORDERS WHERE CUSTOMER_ID = '$uid' AND ORDER_STATUS=0 AND PAYMENT_STATUS=0 AND ORDER_QUANTITY='$finqty' AND ORDER_TOTAL='$fintotal' AND UNIQUE_NOTATION='$uniqueorder'");
    oci_execute($pickorder);
    $fetchorder = oci_fetch_assoc($pickorder);
    $oid = $fetchorder['ORDER_ID'];
    foreach ($cartArr as $key => $val) {
      $ins = oci_parse($conn, "INSERT INTO ORDERS_PRODUCT(FK1_PRODUCT_ID,ORDER_ID,UNIT_PRICE,QUANTITY) VALUES('$key','$oid','" . $val['PRODUCT_PRICE'] . "','" . $val['PRODUCT_QUANTITY'] . "')");
      oci_execute($ins);
    }
    $html = send_invoice($cartArr, $ename, $fintotal, $oid);
    send_email($email, $html, "Order Confirmation Invoice");
    echo "<script>alert('Order Confirmed and status is pending, An invoice has been sent to your email. You can now do the payment through paypal for finalizing the order')</script>";
  } else {
    echo "<script>alert('Some Problem In Order')</script>";
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./images/logo-no-tag-svg.svg">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/fonts.css">
  <link rel="stylesheet" href="./css/colors.css">
  <link rel="stylesheet" href="./css/myaccount.css">
  <title>Confirm Checkout</title>
  <style>
    .paypal {
      margin: 5px auto;

    }

    .paypal input {
      margin: 10px auto;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 5px;
      border-radius: 12px;
      box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;


    }

    .paypal input:hover {
      box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;


    }
  </style>
</head>

<body id="body" class="">
  <div class='account'>
    <h1 class='accheading'>Confirm Checkout</h1>
    <div class='details'>
      <?php
      include('connection.php');
      $uid = $_SESSION['USER_ID'];
      $sql = oci_parse($conn, "SELECT * FROM CUSTOMER WHERE USER_ID='$uid'");
      oci_execute($sql);
      $row = oci_fetch_assoc($sql);

      echo '<form method="post" action="">';
      echo "<input type='hidden' name='eid' value='$row[USER_ID]' readonly>";
      echo '<label>Checkout Name</label> ';
      echo "<input type='text' name='ename' value='$row[CUSTOMER_NAME]' readonly>";
      echo '<label>Checkout Address</label> ';
      echo "<input type='text' name='eaddress' value='$row[ADDRESS]' readonly>";
      echo '<label>Email Address</label> ';
      echo "<input type='text' name='eemail' value='$row[EMAIL_ADDRESS]' readonly>";
      echo '<label>Contact Number</label> ';
      echo "<input type='text' name='ephone' value='$row[CONTACT_NO]' readonly>";
      echo "<h4>List of Purchased Items:</h4>";
      $ptotal = 0;
      $i = 1;
      foreach ($cartArr as $key => $list) {
        $totals = $list['PRODUCT_QUANTITY'] * $list['PRODUCT_PRICE'];
        echo "<h5>Item $i: " . $list['PRODUCT_NAME'] . "\t| Quantity: " . $list['PRODUCT_QUANTITY'] . "\t| Price: " . "£" . number_format(($list['PRODUCT_PRICE'] * $list['PRODUCT_QUANTITY']), 2);
        echo "</h5><hr width='300px'>";
        $i = $i + 1;
        $ptotal = $ptotal + $list['PRODUCT_QUANTITY'] * $list['PRODUCT_PRICE'];
      }
      echo "<h4>TOTAL: £" . number_format($ptotal, 2) . "</h4>";
      $_SESSION['ptotal'] = number_format($ptotal, 2);
      echo "<button class='update' type='submit' name='confirm'><i class='bx bxs-check-circle' ></i>Confirm Order</button>";
      echo "</form>";
      ?>
    </div>
    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" class="paypal">
      <!-- Paypal business test account email id so that you can collect the payments. -->
      <input type="hidden" name="business" value="sb-zkko515872241@business.example.com">
      <!-- Buy Now button. -->
      <input type="hidden" name="cmd" value="_xclick">
      <!-- Details about the item that buyers will purchase. -->
      <input type="hidden" name="item_name" value="<?php if (isset($_POST['confirm'])) {
                                                      echo $oid;
                                                    } else {
                                                      echo "payment";
                                                    } ?>">
      <input type="hidden" name="item_number" value="<?php echo $row['USER_ID']; ?>">
      <input type="hidden" name="amount" value="<?php echo $ptotal ?>">
      <input type="hidden" name="currency_code" value="GBP">
      <input type="hidden" name="rm" value="2">
      <!-- URLs -->
      <input type='hidden' name='cancel_return' value='http://localhost/dbq2/website/failure.php'>
      <input type='hidden' name='return' value='http://localhost/dbq2/website/success.php'>
      <!-- payment button. -->
      <input type="image" name="submit" border="0" src="sandbox/paypal.svg" alt="PayPal - The safer, easier way to pay online">
      <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
    </form>


  </div>
  </div>

</body>

</html>
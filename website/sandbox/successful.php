<?php
session_start();
?>
<?php
echo '<h1>Your payment has been successful</h1>';
include('../function.php');
$catt = getUserCart();

$payment_status = $_POST['payment_status'];
$payment_date = $_POST['payment_date'];
$payment_gross = $_POST['payment_gross'];

echo "payment_status='$payment_status',payment_date='$payment_date',payment_gross='$payment_gross'";
?>
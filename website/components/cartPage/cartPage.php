<?php
include('function.php');
?>
<?php /*
$total=0;
foreach($_SESSION['cart'] as $key1 => $value1){
    echo $value1['item_name'];
    echo $value1['product_price']*$value1['item_quantity'];
    $total=$total+$value1['product_price']*$value1['item_quantity'];
}
echo "Total is: ". $total;*/

?>
<?php
if (isset($_GET['action'])) {
    $pid = $_GET['id'];
    if ($_GET['action'] == "delete") {
        removeProductFromCartById($pid);
    }
}


?>
<?php
if (isset($_POST['chk'])) {
    if (isset($_SESSION['USER_ID'])) {
        $totalquantity = $_SESSION['TQUANTITY'];
        if ($totalquantity > 20) {
            echo '<script>alert("Total quantity must be less than 20 per order")</script>';
        } else {
            $slot_days = $_POST['taskoption'];
            $slot_time = $_POST['timeoption'];

            $_SESSION['slotday'] = $slot_days;
            $_SESSION['slottime'] = $slot_time;
            include('./connection.php');

            header('Location:./confirm.php');
        }
    } else {
        header('Location:./login.php?loginpoint=customerLogin');
    }
} else {
}

?>

<section id="cart-page">
    <div class="container">
        <div class="cart-page-elements">
            <div class="cart-left">
                <div class="cart-left-title">
                    <h1>DBQ MART SHOPPING CART</h1>
                </div>
                <div class="cart-products">
                    <?php
                    $cartArr = getUserFullCart();
                    $total = 0;
                    $total_qty = 0;
                    $i = '1';
                    if (count($cartArr) == 0) {
                        echo "<div class='wish-cart-empty'><h2 align='center'>Cart is empty</h2></div>";
                    }
                    foreach ($cartArr as $key => $list) {
                    ?>

                        <div class="cart-product">
                            <div class="cart-product-left">
                                <div class="cart-product-img">
                                    <img src="./images/products/<?php echo $list['PRODUCT_IMAGE']; ?>" alt="<?php echo $list['PRODUCT_IMAGE'] ?>">
                                </div>
                                <div class="cart-product-contents">
                                    <div class="cart-product-title">
                                        <h6><?php echo $list['PRODUCT_NAME']; ?></h6>
                                    </div>
                                    <div class="cart-product-stock">
                                        <span><?php echo $list['STOCK']; ?></span>
                                    </div>
                                    <div class="cart-product-quantity">
                                        <form method="post" action="">
                                            <input id=inputqty name='qty' type='number' step='1' min='<?php echo $list['MINIMUM_ORDER']; ?>' max='<?php echo $list['MAXIMUM_ORDER']; ?>' value=<?php echo $list['PRODUCT_QUANTITY']; ?>>
                                                                                                                                                                                    
                                            <input name='update' type='submit' value='Set'>
                                            <input name='pid' type='hidden' value='<?php echo $list['PRODUCT_ID']; ?>'>
                                        </form>
                                        <?php
                                        if (isset($_SESSION['USER_ID'])) {
                                            $qty = '';
                                            $uid = '';
                                            $pid = '';
                                            if (isset($_POST['update'])) {
                                                $qty = $_POST['qty'];
                                                $uid = $_SESSION['USER_ID'];
                                                $pid = $_POST['pid'];
                                                manageUserCart($uid, $qty, $pid);
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-product-right">
                                <div class="cart-product-price">
                                    <h6><?php $itotal = $list['PRODUCT_PRICE'] * $list['PRODUCT_QUANTITY'];
                                        echo '£' . number_format($itotal, 2); ?></h6>
                                    <?php $total = $total + $itotal;
                                    $total_qty = $total_qty + $list['PRODUCT_QUANTITY'];
                                    ?>
                                </div>
                                <div class="cart-product-buttons">
                                    <div class="cart-product-delete-button">
                                        <a href="./cart.php?action=delete&id=<?php echo $list["PRODUCT_ID"]; ?>"><i class='bx bx-trash'></i>Remove</a>

                                    </div>
                                    <div class="cart-product-wishlist-button">
                                        <a href="#"><i class='bx bx-heart'></i>Save</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    $_SESSION['TQUANTITY'] = $total_qty; ?>
                </div>
            </div>
            <?php if ($total > 0) { ?>
                <div class="cart-right">
                    <div class="cart-right-title">
                        <h5>Pickup</h5>
                    </div>
                    <div class="cart-right-top-part">
                        <div class="pickup-date">
                            <span>Collection</span>
                        </div>
                    </div>
                    <div class="cart-right-middle-part">
                        <div class="cart-promo">
                            <form method='post' action=''>
                                <div class="promoinput">
                                </div>
                                <div class="promobutton">
                                    <?php
                                    date_default_timezone_set('Asia/Kathmandu');
                                    $date = date("Y-m-d");
                                    //$date = date("2021-07-01");
                                    //echo $date;
                                    $day = date("D", strtotime($date));

                                    switch ($day) {

                                        case "Sun":
                                            $a = strtotime($date . "+ 3 days");
                                            $b = strtotime($date . "+ 4 days");
                                            $c = strtotime($date . "+ 5 days");
                                            break;

                                        case "Mon":
                                            $a = strtotime($date . "+ 2 days");
                                            $b = strtotime($date . "+ 3 days");
                                            $c = strtotime($date . "+ 4 days");
                                            break;

                                        case "Tue":
                                            $a = strtotime($date . "+ 1 days");
                                            $b = strtotime($date . "+ 2 days");
                                            $c = strtotime($date . "+ 3 days");
                                            break;

                                        case "Wed":
                                            $a = strtotime($date . "+ 1 days");
                                            $b = strtotime($date . "+ 2 days");
                                            $c = strtotime($date . "+ 7 days");
                                            break;

                                        case "Thu":
                                            $a = strtotime($date . "+ 1 days");
                                            $b = strtotime($date . "+ 6 days");
                                            $c = strtotime($date . "+ 7 days");
                                            break;

                                        case "Fri":
                                            $a = strtotime($date . "+ 5 days");
                                            $b = strtotime($date . "+ 6 days");
                                            $c = strtotime($date . "+ 7 days");
                                            break;

                                        case "Sat":
                                            $a = strtotime($date . "+ 4 days");
                                            $b = strtotime($date . "+ 5 days");
                                            $c = strtotime($date . "+ 6 days");
                                            break;
                                    }



                                    $x1 = date("l-m-d-Y", $a);
                                    $y2 = date("l-m-d-Y", $b);
                                    $z3 = date("l-m-d-Y", $c);


                                    ?>
                                    <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="taskoption">
                                        <option value=<?php echo $x1 ?>><?php echo $x1 ?></option>
                                        <option value=<?php echo $y2 ?>><?php echo $y2 ?></option>
                                        <option value=<?php echo $z3 ?>><?php echo $z3 ?></option>
                                    </select>

                                    <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="timeoption">
                                        <option value="10-13"><?php echo "10-13" ?></option>
                                        <option value="13-16"><?php echo "13-16" ?></option>
                                        <option value="16-19"><?php echo "16-19" ?></option>
                                    </select>

                                </div>
                        </div>
                        <span>20% off Discount</span>
                    </div>
                    <div class="cart-right-bottom-part">
                        <div class="cart-right-bottom-part-left">
                            <div class="cart-right-bottom-part-left-main-title">
                                <h6>Subtotal</h6>
                            </div>
                            <div class="cart-right-bottom-part-left-sub-title">
                                <h6>Discount</h6>
                            </div>
                            <div class="cart-right-bottom-part-left-sub-title">
                                <h6>Tax</h6>
                            </div>
                        </div>
                        <div class="cart-right-bottom-part-right">
                            <div class="cart-right-bottom-part-right-main-title">
                                <h6><?php echo '£' . number_format($total, 2);
                                    $_SESSION['total'] = number_format($total, 2); ?></h6>
                            </div>
                            <div class="cart-right-bottom-part-right-sub-title">
                                <h6>0</h6>
                            </div>
                            <div class="cart-right-bottom-part-right-sub-title">
                                <h6>Included</h6>
                            </div>
                        </div>
                    </div>
                    <div class="cart-right-total-part">
                        <div class="cart-right-bottom-part-left">
                            <div class="cart-right-bottom-part-left-main-title">
                                <h6>Total</h6>
                            </div>
                        </div>
                        <div class="cart-right-bottom-part-right">
                            <div class="cart-right-bottom-part-right-main-title">
                                <h6><?php echo '£' . number_format($total, 2); ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="cart-right-buttons-part">
                        <div class="checkout-button">
                            <button type='submit' name='chk'>Proceed to Checkout</button>
                        </div>
                        </form>
                        <div class="continue-shopping-button">
                            <a href='./products.php'><button>Continue Shopping</button></a>
                        </div>
                    </div>
                </div>
        </div>
    <?php } ?>
</section>
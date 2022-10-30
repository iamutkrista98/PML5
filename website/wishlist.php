<?php
session_start();
?>
<?php
include('function.php');
?>
<?php

if (isset($_GET['remove'])) {
    $pid = $_GET['pid'];
    removeProductFromWishlistById($pid);
}


?>
<?php
if (isset($_GET['add'])) {
    $pid = $_GET['pid'];
    $qty = $_GET['quantity'];
    //WHEN USER LOGGED IN
    if (isset($_SESSION['USER_ID'])) {
        $uid = $_SESSION['USER_ID'];

        manageUserCart($uid, $qty, $pid);
    } else {
        $_SESSION['cart'][$pid]['qty'] = $qty;
        echo '<script>alert("Product added to the cart successfully ...!")</script>';
        echo '<script>window.location="./products.php"</script>';
    }
    $totalCart = count(getUserFullCart());
    $arr = array('totalCartProduct' => $totalCart);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WishList</title>
    <link rel="icon" type="image/x-icon" href="./images/logo-no-tag-svg.svg">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/colors.css">
    <link rel="stylesheet" href="./css/wishlist.css">

</head>

<body id="body" class=''>
    <header>
        <?PHP
        include "./components/nav/nav.php";
        ?>
    </header>
    <!-- Header Ends -->
    <?PHP
    include "./components/searchbox/searchbox.php";
    include "./components/darkToggle/darkToggle.php";
    ?>

    <!-- Dark Toggle Ends -->
    <div class='wishlist'>
        <h1 class='wishhead'>DBQ MART WISH LIST</h1>
        <?php
        $WishlistArr = getUserFullWishlist();
        if (count($WishlistArr) == 0) {
            echo "<div class='wish-cart-empty'><h2 align='center'>Wishlist is empty</h2></div>";
        }
        $i = '1';
        foreach ($WishlistArr as $key => $list) {
        ?>
            <div class='wish-cart'>
                <form action="" method='get' class='wish-items'>
                    <div class='buttons'>
                        <button class='removebtn' type='submit' name='remove'><i class='bx bxs-trash-alt'></i><span>Remove</span></button>
                        <?php
                        if ($list['STOCK'] != 0) {
                            echo '<button class="addbtn" type="submit" name="add"><i class="bx bxs-cart-add"></i><span>Add</span></button>';
                        } else {
                        }
                        ?>

                        <input type="hidden" name="quantity" value="<?php echo $list['MINIMUM_ORDER']; ?>">



                    </div>
                    <img src='images/products/<?php echo $list['PRODUCT_IMAGE']; ?>'>
                    <h2>Item Name:<?php echo $list['PRODUCT_NAME']; ?></h2>
                    <h2>Per Unit Price: £<?php echo $list['PRODUCT_PRICE']; ?></h2>
                    <h2>Added On: <?php echo $list['ADDED_ON']; ?></h2>
                    <input type='hidden' name='pid' value='<?php echo $list['PRODUCT_ID']; ?>'>
                </form>
            </div>
        <?php } ?>
    </div>



    <footer>
        <?PHP
        include "./components/footer/footer.php";
        ?>
    </footer>
    <!-- Footer Ends -->
    <script src="./js/main.js"></script>
    <script src="./js/hero.js"></script>
</body>

</html>
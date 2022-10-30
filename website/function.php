<?php
/* getting details from customer table   */
function getCustomerDetailById()
{
	include('./connection.php');
	$data['name'] = '';
	$data['email'] = '';
	$data['mobile'] = '';
	$data['address'] = '';
	if (isset($_SESSION['USER_ID'])) {
		$res = oci_parse($con, "SELECT * FROM CUSTOMER WHERE USER_ID=" . $_SESSION['USER_ID']);
		oci_execute($res);
		$row = oci_fetch_assoc($res);
		$data['name'] = $row['CUSTOMER_NAME'];
		$data['email'] = $row['EMAIL_ADDRESS'];
		$data['address'] = $row['ADDRESS'];
		$data['mobile'] = $row['CONTACT_NO'];
	}
	return $data;
}
/*getting details through argument as product id*/
function getProductDetailById($id)
{
	include('connection.php');
	$res = oci_parse($conn, "SELECT * FROM PRODUCT WHERE PRODUCT_ID='$id' ");
	oci_execute($res);
	$row = oci_fetch_assoc($res);
	return $row;
}

/*---------------------cart related functions--------------*/
function getUserCart()
{
	include('connection.php');
	$arr = array();
	$id = $_SESSION['USER_ID'];
	$res = oci_parse($conn, "SELECT * FROM CART_PRODUCT WHERE ADDED_BY='$id'");
	oci_execute($res);
	while ($row = oci_fetch_assoc($res)) {
		$arr[] = $row;
	}
	return $arr;
}

function removeProductFromCartById($id)
{
	if (isset($_SESSION['USER_ID'])) {
		include('connection.php');
		$del = $_SESSION['USER_ID'];
		$res = oci_parse($conn, "DELETE FROM CART_PRODUCT WHERE FK2_PRODUCT_ID='$id' and ADDED_BY='$del'");
		oci_execute($res);
		$check = oci_parse($conn, "SELECT * FROM CART_PRODUCT WHERE ADDED_BY='$del'");
		oci_execute($check);
		$countitem = 0;
		while ($rowcheck = oci_fetch_assoc($check)) {
			$countitem = oci_num_rows($check);
		}
		if ($countitem == 0) {
			$free = oci_parse($conn, "DELETE FROM CART WHERE FK1_USER_ID='$del'");
			oci_execute($free);
		} else {
		}
		echo '<script>alert("Product removed from the cart ...!")</script>';
		echo '<script>window.location="./cart.php"</script>';
	} else {
		unset($_SESSION['cart'][$id]);
		echo '<script>alert("Product removed from the cart ...!")</script>';
		echo '<script>window.location="./cart.php"</script>';
	}
}


function getUserFullCart()
{
	$cartArr = array();
	if (isset($_SESSION['USER_ID'])) {
		$getUserCart = getUserCart();
		//prx($getUserCart);
		$cartArr = array();
		foreach ($getUserCart as $list) {
			//prx($list);
			$cartArr[$list['FK2_PRODUCT_ID']]['PRODUCT_QUANTITY'] = $list['QTY'];
			$getProductDetail = getProductDetailById($list['FK2_PRODUCT_ID']);
			$cartArr[$list['FK2_PRODUCT_ID']]['PRODUCT_ID'] = $getProductDetail['PRODUCT_ID'];
			$cartArr[$list['FK2_PRODUCT_ID']]['PRODUCT_PRICE'] = $getProductDetail['PRODUCT_PRICE'];
			$cartArr[$list['FK2_PRODUCT_ID']]['PRODUCT_NAME'] = $getProductDetail['PRODUCT_NAME'];
			$cartArr[$list['FK2_PRODUCT_ID']]['PRODUCT_IMAGE'] = $getProductDetail['PRODUCT_IMAGE'];
			$cartArr[$list['FK2_PRODUCT_ID']]['MAXIMUM_ORDER'] = $getProductDetail['MAXIMUM_ORDER'];
			$cartArr[$list['FK2_PRODUCT_ID']]['MINIMUM_ORDER'] = $getProductDetail['MINIMUM_ORDER'];
			$cartArr[$list['FK2_PRODUCT_ID']]['STOCK'] = $getProductDetail['PRODUCT_STOCK'];
		}
	} else {
		if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
			foreach ($_SESSION['cart'] as $key => $val) {
				//prx($val);
				$cartArr[$key]['PRODUCT_QUANTITY'] = $val['qty'];
				$getProductDetail = getProductDetailById($key);
				$cartArr[$key]['PRODUCT_ID'] = $getProductDetail['PRODUCT_ID'];
				$cartArr[$key]['PRODUCT_PRICE'] = $getProductDetail['PRODUCT_PRICE'];
				$cartArr[$key]['PRODUCT_NAME'] = $getProductDetail['PRODUCT_NAME'];
				$cartArr[$key]['PRODUCT_IMAGE'] = $getProductDetail['PRODUCT_IMAGE'];
				$cartArr[$key]['MAXIMUM_ORDER'] = $getProductDetail['MAXIMUM_ORDER'];
				$cartArr[$key]['MINIMUM_ORDER'] = $getProductDetail['MINIMUM_ORDER'];
				$cartArr[$key]['STOCK'] = $getProductDetail['PRODUCT_STOCK'];
			}
		}
	}
	return $cartArr;
}


?>

<?php
function manageUserCart($uid, $qty, $pid)
{
	if (isset($_SESSION['USER_ID'])) {
		include('connection.php');
		$sql = "SELECT * FROM CART_PRODUCT WHERE ADDED_BY='$uid' and FK2_PRODUCT_ID='$pid'";
		$res = oci_parse($conn, $sql);
		oci_execute($res);

		//$check = oci_fetch_all($res,$con);
		$check = oci_fetch($res);
		oci_execute($res);

		if ($check > 0) {
			$row = oci_fetch_assoc($res);
			$cid = $row['FK2_PRODUCT_ID'];
			$sql = oci_parse($conn, "UPDATE CART_PRODUCT SET QTY='$qty' where FK2_PRODUCT_ID='$cid'");

			oci_execute($sql);
			echo '<script>alert("Product updated in cart ...!")</script>';
			echo '<script>window.location="./cart.php"</script>';
		} else {
			$inst = "INSERT INTO CART(FK1_USER_ID) VALUES('$uid')";
			$instres = oci_parse($conn, $inst);
			oci_execute($instres);
			$pre = oci_parse($conn, "SELECT * FROM CART WHERE FK1_USER_ID='$uid'");
			oci_execute($pre);
			while ($cartrow = oci_fetch_assoc($pre)) {
				$ciid = $cartrow['CART_ID'];
			}

			$added_on = date('j:M:y H:i:s');
			$sql = oci_parse($conn, "INSERT INTO CART_PRODUCT(ADDED_BY,FK1_CART_ID,FK2_PRODUCT_ID,QTY,ADDED_ON) 
		values('$uid','$ciid','$pid','$qty',to_date('$added_on', 
		'DD:MON:YY HH24:MI:SS')) ");
			oci_execute($sql);
			echo '<script>alert("Product added to the cart successfully ...!")</script>';
		}
	} else {
	}
}

function emptyCart()
{
	if (isset($_SESSION['USER_ID'])) {
		include('connection.php');
		$uid = $_SESSION['USER_ID'];
		$res = oci_parse($conn, "DELETE FROM CART_PRODUCT WHERE ADDED_BY='$uid'");
		oci_execute($res);
		$check = oci_parse($conn, "SELECT * FROM CART_PRODUCT WHERE ADDED_BY='$uid'");
		oci_execute($check);
		$countitem = 0;
		while ($rowcheck = oci_fetch_assoc($check)) {
			$countitem = oci_num_rows($check);
		}
		if ($countitem == 0) {
			$free = oci_parse($conn, "DELETE FROM CART WHERE FK1_USER_ID='$uid'");
			oci_execute($free);
		} else {
		}
	} else {
		unset($_SESSION['cart']);
	}
}


function dateFormat($date)
{
	$str = strtotime($date);
	return date('d-m-Y', $str);
}

/*Wishlist related functions*/
function getUserWishlist()
{
	include('connection.php');
	$arr = array();
	$id = $_SESSION['USER_ID'];
	$res = oci_parse($conn, "SELECT * FROM WISHLIST_PRODUCT WHERE ADDED_BY='$id'");
	oci_execute($res);
	while ($row = oci_fetch_assoc($res)) {
		$arr[] = $row;
	}
	return $arr;
}

function manageUserWishlist($uid, $pid)
{
	include('connection.php');
	$uid = $_SESSION['USER_ID'];
	$sql = "SELECT * FROM WISHLIST_PRODUCT WHERE ADDED_BY='$uid' and FK1_PRODUCT_ID='$pid'";
	$res = oci_parse($conn, $sql);
	oci_execute($res);

	//$check = oci_fetch_all($res,$con);
	$check = oci_fetch($res);
	oci_execute($res);

	if ($check > 0) {
		echo '<script>alert("Product already in the wishlist ...!")</script>';
	} else {

		$inst = "INSERT INTO WISHLIST(FK1_USER_ID) VALUES('$uid')";
		$instres = oci_parse($conn, $inst);
		oci_execute($instres);

		$pre = oci_parse($conn, "SELECT * FROM WISHLIST WHERE FK1_USER_ID='$uid'");
		oci_execute($pre);
		while ($wishrow = oci_fetch_assoc($pre)) {
			$wid = $wishrow['WISHLIST_ID'];
		}

		$added_on = date('j:M:y H:i:s');
		$sql = oci_parse($conn, "INSERT INTO WISHLIST_PRODUCT(ADDED_BY,FK1_PRODUCT_ID,FK2_WISHLIST_ID,ADDED_ON) 
			values('$uid','$pid','$wid',to_date('$added_on', 'DD:MON:YY HH24:MI:SS')) ");
		oci_execute($sql);
		echo '<script>alert("Product added to the wishlist successfully ...!")</script>';
	}
}
function getUserFullWishlist()
{
	$WishArr = array();
	if (isset($_SESSION['USER_ID'])) {
		$getUserWishlist = getUserWishlist();
		//prx($getUserCart);
		$WishArr = array();
		foreach ($getUserWishlist as $list) {
			$WishArr[$list['FK1_PRODUCT_ID']]['ADDED_ON'] = $list['ADDED_ON'];
			//prx($list);
			$getProductDetail = getProductDetailById($list['FK1_PRODUCT_ID']);
			$WishArr[$list['FK1_PRODUCT_ID']]['PRODUCT_ID'] = $getProductDetail['PRODUCT_ID'];
			$WishArr[$list['FK1_PRODUCT_ID']]['PRODUCT_PRICE'] = $getProductDetail['PRODUCT_PRICE'];
			$WishArr[$list['FK1_PRODUCT_ID']]['PRODUCT_NAME'] = $getProductDetail['PRODUCT_NAME'];
			$WishArr[$list['FK1_PRODUCT_ID']]['PRODUCT_IMAGE'] = $getProductDetail['PRODUCT_IMAGE'];
			$WishArr[$list['FK1_PRODUCT_ID']]['MAXIMUM_ORDER'] = $getProductDetail['MAXIMUM_ORDER'];
			$WishArr[$list['FK1_PRODUCT_ID']]['MINIMUM_ORDER'] = $getProductDetail['MINIMUM_ORDER'];
			$WishArr[$list['FK1_PRODUCT_ID']]['STOCK'] = $getProductDetail['PRODUCT_STOCK'];
		}
	} else {
	}
	return $WishArr;
}

function removeProductFromWishlistById($id)
{
	if (isset($_SESSION['USER_ID'])) {
		include('connection.php');
		$del = $_SESSION['USER_ID'];
		$res = oci_parse($conn, "DELETE FROM WISHLIST_PRODUCT WHERE FK1_PRODUCT_ID='$id' and ADDED_BY='$del'");
		oci_execute($res);
		$check = oci_parse($conn, "SELECT * FROM WISHLIST_PRODUCT WHERE ADDED_BY='$id'");
		oci_execute($check);
		$countitem = 0;
		while ($rowcheck = oci_fetch_assoc($check)) {
			$countitem = oci_num_rows($check);
		}
		if ($countitem == 0) {
			$free = oci_parse($conn, "DELETE FROM WISHLIST WHERE FK1_USER_ID='$del'");
			oci_execute($free);
		} else {
		}
		echo '<script>alert("Product removed from the wishlist ...!")</script>';
		echo '<script>window.location="wishlist.php"</script>';
	} else {
	}
}



//for email validation//
function generate_token()
{
	$token = str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz");
	return $token = substr($token, 0, 15);
}
//for trader username and password generation
function generate_username()
{
	$username = str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz");
	return $username = substr($username, 0, 12);
}

function generate_password()
{
	$password = str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
	return $password = substr($password, 0, 10);
}
function generate_unique_order_notation()
{
	$notation = str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz123456789");
	return $notation = substr($notation, 0, 8);
}


function getVerifyMail($cname, $username, $email, $npassword, $address, $token)
{
	$html = '
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="x-apple-disable-message-reformatting" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="css/fonts.css">
	<title></title>
	<style type="text/css" rel="stylesheet" media="all">
		/* Base ------------------------------ */

		@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");

		body {
			width: 100% !important;
			height: 100%;
			margin: 0;
			-webkit-text-size-adjust: none;
		}

		a {
			color: #3869D4;
		}

		a img {
			border: none;
		}

		td {
			word-break: break-word;
		}

		.preheader {
			display: none !important;
			visibility: hidden;
			font-size: 1px;
			line-height: 1px;
			max-height: 0;
			max-width: 0;
			opacity: 0;
			overflow: hidden;
		}

		/* Type ------------------------------ */

		body,
		td,
		th {
			font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
		}

		h1 {
			margin-top: 0;
			color: #333333;
			font-size: 22px;
			font-weight: bold;
			text-align: left;
		}
        .verify{
            padding:10px;
            margin:30px auto;
        }
        .verify a{
            text-decoration:none;
            font-size:18px;
        }

		h2 {
			margin-top: 0;
			color: #333333;
			font-size: 16px;
			font-weight: bold;
			text-align: left;
		}

		h3 {
			margin-top: 0;
			color: #333333;
			font-size: 14px;
			font-weight: bold;
			text-align: left;
		}

		td,
		th {
			font-size: 16px;
		}

		p,
		ul,
		ol,
		blockquote {
			margin: .4em 0 1.1875em;
			font-size: 16px;
			line-height: 1.625;
            text-align:justify;
		}

		p.sub {
			font-size: 13px;
		}

		/* Utilities ------------------------------ */

		.align-right {
			text-align: right;
		}

		.align-left {
			text-align: left;
		}

		.align-center {
			text-align: center;
		}

		/* Buttons ------------------------------ */

		.button {
			background-color: #3869D4;
			border-top: 10px solid #3869D4;
			border-right: 18px solid #3869D4;
			border-bottom: 10px solid #3869D4;
			border-left: 18px solid #3869D4;
			display: inline-block;
			color: #FFF;
			text-decoration: none;
			border-radius: 3px;
			box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
			-webkit-text-size-adjust: none;
			box-sizing: border-box;
		}

		.button--green {
			background-color: #22BC66;
			border-top: 10px solid #22BC66;
			border-right: 18px solid #22BC66;
			border-bottom: 10px solid #22BC66;
			border-left: 18px solid #22BC66;
		}

		.button--red {
			background-color: #FF6136;
			border-top: 10px solid #FF6136;
			border-right: 18px solid #FF6136;
			border-bottom: 10px solid #FF6136;
			border-left: 18px solid #FF6136;
		}

		@media only screen and (max-width: 500px) {
			.button {
				width: 100% !important;
				text-align: center !important;
			}
		}

		/* Attribute list ------------------------------ */

		.attributes {
			margin: 0 0 21px;
		}

		.attributes_content {
			background-color: #F4F4F7;
			padding: 16px;
		}

		.attributes_item {
			padding: 0;
		}

		/* Related Items ------------------------------ */

		.related {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.related_item {
			padding: 10px 0;
			color: #CBCCCF;
			font-size: 15px;
			line-height: 18px;
		}

		.related_item-title {
			display: block;
			margin: .5em 0 0;
		}

		.related_item-thumb {
			display: block;
			padding-bottom: 10px;
		}

		.related_heading {
			border-top: 1px solid #CBCCCF;
			text-align: center;
			padding: 25px 0 10px;
		}

		/* Discount Code ------------------------------ */

		.discount {
			width: 100%;
			margin: 0;
			padding: 24px;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
			border: 2px dashed #CBCCCF;
		}

		.discount_heading {
			text-align: center;
		}

		.discount_body {
			text-align: center;
			font-size: 15px;
		}

		/* Social Icons ------------------------------ */

		.social {
			width: auto;
		}

		.social td {
			padding: 0;
			width: auto;
		}

		.social_icon {
			height: 20px;
			margin: 0 8px 10px 8px;
			padding: 0;
		}
        .email-masthead td{
            width:50%;
            margin: 0 auto;
        }


		/* Data table ------------------------------ */

		.purchase {
			width: 100%;
			margin: 0;
			padding: 35px 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_content {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_item {
			padding: 10px 0;
			color: #51545E;
			font-size: 15px;
			line-height: 18px;
		}

		.purchase_heading {
			padding-bottom: 8px;
			border-bottom: 1px solid #EAEAEC;
		}

		.purchase_heading p {
			margin: 0;
			color: #85878E;
			font-size: 12px;
		}

		.purchase_footer {
			padding-top: 15px;
			border-top: 1px solid #EAEAEC;
		}

		.purchase_total {
			margin: 0;
			text-align: right;
			font-weight: bold;
			color: #333333;
		}

		.purchase_total--label {
			padding: 0 15px 0 0;
		}

		body {
			background-color: #F4F4F7;
			color: #51545E;
		}

		p {
			color: #51545E;
		}

		p.sub {
			color: #6B6E76;
		}

		.email-wrapper {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
		}

		.email-content {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		/* Masthead ----------------------- */

		.email-masthead {
			padding: 25px 0;
			text-align: center;
            background-color: #F2B34E;
		}

		.email-masthead_logo {
			width: 94px;
		}



		.email-masthead_name {
			font-size: 16px;
			font-weight: bold;
			color: black;
			text-decoration: none;
			text-shadow: 0 1px 0 white;
		}

		/* Body ------------------------------ */

		.email-body {
			width: 100%;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            
		}

		.email-body_inner {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
            border-radius:12px;
		}

		.email-footer {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.email-footer p {
			color: #6B6E76;
		}

		.body-action {
			width: 100%;
			margin: 30px auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.body-sub {
			margin-top: 25px;
			padding-top: 25px;
			border-top: 1px solid #EAEAEC;
		}

		.content-cell {
			padding: 35px;
		}


		.dblogo {
			width: 50%;


		}

		.dblogo img {

			width: 30vh;
			margin: 0 auto;
		}

		/*Media Queries ------------------------------ */

		@media only screen and (max-width: 600px) {

			.email-body_inner,
			.email-footer {
				width: 100% !important;
			}
		}

		@media (prefers-color-scheme: dark) {

			body,
			.email-body,
			.email-body_inner,
			.email-content,
			.email-wrapper,
			.email-masthead,
			.email-footer {
				background-color: #333333 !important;
				color: #FFF !important;
			}

			p,
			ul,
			ol,
			blockquote,
			h1,
			h2,
			h3 {
				color: #FFF !important;
			}

			.attributes_content,
			.discount {
				background-color: #222 !important;
			}

			.email-masthead_name {
				text-shadow: none !important;
			}
		}
	</style>
	<!--[if mso]>
		<style type="text/css">
		  .f-fallback  {
			font-family: Arial, sans-serif;
		  }
		</style>
	  <![endif]-->
</head>

<body>
	<span class="preheader">This is a verification email for your interest in registering at DBQ MART</span>
	<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
					<tr>
						<td class="email-masthead">

							<a href="" class="f-fallback email-masthead_name">
								Daily Bread Quintessential (DBQ MART)
							</a>
						</td>
					</tr>
					<!-- Email Body -->
					<tr>
						<td class="email-body" width="100%" cellpadding="0" cellspacing="0">
							<table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
								role="presentation">
								<!-- Body content -->
								<tr>
									<td class="content-cell">
										<div class="f-fallback">
											<h1>Hello ' . $cname . ',</h1>
											<p>This is a verification email for your registration at DBQ MART. The details you submitted are here in attached for reference.
                                            You are one step away from great things coming your way.</p>
											<table class="attributes" width="100%" cellpadding="0" cellspacing="0"
												role="presentation">
												<tr>
													<td class="attributes_content">
														<table width="100%" cellpadding="0" cellspacing="0"
															role="presentation">
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Customer Name:</strong>' . $cname . '
																	</span>
																</td>
															</tr>
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Username:</strong>' . $username . '
																	</span>
																</td>
															</tr>
                                                            <tr>
                                                            <td class="attributes_item">
                                                                <span class="f-fallback">
                                                                    <strong>Password:</strong>' . $npassword . '
                                                        
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        <td class="attributes_item">
                                                            <span class="f-fallback">
                                                                <strong>Address:</strong>' . $address . '
                                                    
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    <td class="attributes_item">
                                                        <span class="f-fallback">
                                                            <strong><button class="verify">' . '<a href="http://localhost/dbq2/website/verify.php?email=' . $email . '&token=' . $token . '">Verify</a></button></strong>

                                                
                                                        </span>
                                                    </td>
                                                </tr>														</table>
													</td>
												</tr>
											</table>
											<p>This is a system generated email. Please do not reply to this email!
											</p>
											<p>Cheers,
												<br>Daily Bread Quintessential(DBQ MART)
												<div class="dblogo"><img src="cid:logo"/></div>

											</p>
											<!-- Sub copy -->

										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>
</body>

</html>
';
	return $html;
}

/*verify Trader*/
function authenticateTrader($tname, $temail, $sname, $tcat, $saddress, $tphone, $genname, $genpassword)
{
	$html = '
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="x-apple-disable-message-reformatting" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="css/fonts.css">
	<title></title>
	<style type="text/css" rel="stylesheet" media="all">
		/* Base ------------------------------ */

		@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");

		body {
			width: 100% !important;
			height: 100%;
			margin: 0;
			-webkit-text-size-adjust: none;
		}

		a {
			color: #3869D4;
		}

		a img {
			border: none;
		}

		td {
			word-break: break-word;
		}

		.preheader {
			display: none !important;
			visibility: hidden;
			font-size: 1px;
			line-height: 1px;
			max-height: 0;
			max-width: 0;
			opacity: 0;
			overflow: hidden;
		}

		/* Type ------------------------------ */

		body,
		td,
		th {
			font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
		}

		h1 {
			margin-top: 0;
			color: #333333;
			font-size: 22px;
			font-weight: bold;
			text-align: left;
		}
        .verify{
            padding:10px;
            margin:30px auto;
        }
        .verify a{
            text-decoration:none;
            font-size:18px;
        }

		h2 {
			margin-top: 0;
			color: #333333;
			font-size: 16px;
			font-weight: bold;
			text-align: left;
		}

		h3 {
			margin-top: 0;
			color: #333333;
			font-size: 14px;
			font-weight: bold;
			text-align: left;
		}

		td,
		th {
			font-size: 16px;
		}

		p,
		ul,
		ol,
		blockquote {
			margin: .4em 0 1.1875em;
			font-size: 16px;
			line-height: 1.625;
            text-align:justify;
		}

		p.sub {
			font-size: 13px;
		}

		/* Utilities ------------------------------ */

		.align-right {
			text-align: right;
		}

		.align-left {
			text-align: left;
		}

		.align-center {
			text-align: center;
		}

		/* Buttons ------------------------------ */

		.button {
			background-color: #3869D4;
			border-top: 10px solid #3869D4;
			border-right: 18px solid #3869D4;
			border-bottom: 10px solid #3869D4;
			border-left: 18px solid #3869D4;
			display: inline-block;
			color: #FFF;
			text-decoration: none;
			border-radius: 3px;
			box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
			-webkit-text-size-adjust: none;
			box-sizing: border-box;
		}

		.button--green {
			background-color: #22BC66;
			border-top: 10px solid #22BC66;
			border-right: 18px solid #22BC66;
			border-bottom: 10px solid #22BC66;
			border-left: 18px solid #22BC66;
		}

		.button--red {
			background-color: #FF6136;
			border-top: 10px solid #FF6136;
			border-right: 18px solid #FF6136;
			border-bottom: 10px solid #FF6136;
			border-left: 18px solid #FF6136;
		}

		@media only screen and (max-width: 500px) {
			.button {
				width: 100% !important;
				text-align: center !important;
			}
		}

		/* Attribute list ------------------------------ */

		.attributes {
			margin: 0 0 21px;
		}

		.attributes_content {
			background-color: #F4F4F7;
			padding: 16px;
		}

		.attributes_item {
			padding: 0;
		}

		/* Related Items ------------------------------ */

		.related {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.related_item {
			padding: 10px 0;
			color: #CBCCCF;
			font-size: 15px;
			line-height: 18px;
		}

		.related_item-title {
			display: block;
			margin: .5em 0 0;
		}

		.related_item-thumb {
			display: block;
			padding-bottom: 10px;
		}

		.related_heading {
			border-top: 1px solid #CBCCCF;
			text-align: center;
			padding: 25px 0 10px;
		}

		/* Discount Code ------------------------------ */

		.discount {
			width: 100%;
			margin: 0;
			padding: 24px;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
			border: 2px dashed #CBCCCF;
		}

		.discount_heading {
			text-align: center;
		}

		.discount_body {
			text-align: center;
			font-size: 15px;
		}

		/* Social Icons ------------------------------ */

		.social {
			width: auto;
		}

		.social td {
			padding: 0;
			width: auto;
		}

		.social_icon {
			height: 20px;
			margin: 0 8px 10px 8px;
			padding: 0;
		}
        .email-masthead td{
            width:50%;
            margin: 0 auto;
        }


		/* Data table ------------------------------ */

		.purchase {
			width: 100%;
			margin: 0;
			padding: 35px 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_content {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_item {
			padding: 10px 0;
			color: #51545E;
			font-size: 15px;
			line-height: 18px;
		}

		.purchase_heading {
			padding-bottom: 8px;
			border-bottom: 1px solid #EAEAEC;
		}

		.purchase_heading p {
			margin: 0;
			color: #85878E;
			font-size: 12px;
		}

		.purchase_footer {
			padding-top: 15px;
			border-top: 1px solid #EAEAEC;
		}

		.purchase_total {
			margin: 0;
			text-align: right;
			font-weight: bold;
			color: #333333;
		}

		.purchase_total--label {
			padding: 0 15px 0 0;
		}

		body {
			background-color: #F4F4F7;
			color: #51545E;
		}

		p {
			color: #51545E;
		}

		p.sub {
			color: #6B6E76;
		}

		.email-wrapper {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
		}

		.email-content {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		/* Masthead ----------------------- */

		.email-masthead {
			padding: 25px 0;
			text-align: center;
            background-color: #F2B34E;
		}

		.email-masthead_logo {
			width: 94px;
		}



		.email-masthead_name {
			font-size: 16px;
			font-weight: bold;
			color: black;
			text-decoration: none;
			text-shadow: 0 1px 0 white;
		}

		/* Body ------------------------------ */

		.email-body {
			width: 100%;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            
		}

		.email-body_inner {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
            border-radius:12px;
		}

		.email-footer {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.email-footer p {
			color: #6B6E76;
		}

		.body-action {
			width: 100%;
			margin: 30px auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.body-sub {
			margin-top: 25px;
			padding-top: 25px;
			border-top: 1px solid #EAEAEC;
		}

		.content-cell {
			padding: 35px;
		}


		.dblogo {
			width: 50%;


		}

		.dblogo img {

			width: 30vh;
			margin: 0 auto;
		}

		/*Media Queries ------------------------------ */

		@media only screen and (max-width: 600px) {

			.email-body_inner,
			.email-footer {
				width: 100% !important;
			}
		}

		@media (prefers-color-scheme: dark) {

			body,
			.email-body,
			.email-body_inner,
			.email-content,
			.email-wrapper,
			.email-masthead,
			.email-footer {
				background-color: #333333 !important;
				color: #FFF !important;
			}

			p,
			ul,
			ol,
			blockquote,
			h1,
			h2,
			h3 {
				color: #FFF !important;
			}

			.attributes_content,
			.discount {
				background-color: #222 !important;
			}

			.email-masthead_name {
				text-shadow: none !important;
			}
		}
	</style>
	<!--[if mso]>
		<style type="text/css">
		  .f-fallback  {
			font-family: Arial, sans-serif;
		  }
		</style>
	  <![endif]-->
</head>

<body>
	<span class="preheader">This is a verification email for your interest in registering at DBQ MART</span>
	<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
					<tr>
						<td class="email-masthead">

							<a href="" class="f-fallback email-masthead_name">
								Daily Bread Quintessential (DBQ MART)
							</a>
						</td>
					</tr>
					<!-- Email Body -->
					<tr>
						<td class="email-body" width="100%" cellpadding="0" cellspacing="0">
							<table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
								role="presentation">
								<!-- Body content -->
								<tr>
									<td class="content-cell">
										<div class="f-fallback">
											<h1>Hello ' . 'admin' . ',</h1>
											<p>This is request email from a trader for registration at DBQ MART. The details the trader submitted are here in attached for reference.
                                            </p>
											<table class="attributes" width="100%" cellpadding="0" cellspacing="0"
												role="presentation">
												<tr>
													<td class="attributes_content">
														<table width="100%" cellpadding="0" cellspacing="0"
															role="presentation">
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Trader Name:</strong>' . $tname . '  
																	</span>
																</td>
															</tr>
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Trader Email:</strong>' . $temail . '
																	</span>
																</td>
															</tr>
                                                            <tr>
                                                            <td class="attributes_item">
                                                                <span class="f-fallback">
                                                                    <strong>Shop Name:</strong>' . $sname . '
                                                        
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        <td class="attributes_item">
                                                            <span class="f-fallback">
                                                                <strong>Shop Address:</strong>' . $saddress . '
                                                    
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
													
													<td class="attributes_item">
														<span class="f-fallback">
															<strong>Contact No:</strong>' . $tphone . '
												
														</span>
													</td>
												</tr>
												<tr>
													
												<td class="attributes_item">
													<span class="f-fallback">
														<strong>Category:</strong>' . $tcat . '
											
													</span>
												</td>
											</tr>
												<tr>
                                                    <td class="attributes_item">
                                                        <span class="f-fallback">
                                                            <strong><button class="verify">' . '<a href="http://localhost/dbq2/website/verifytrader.php?email=' . $temail . '&tname=' . $tname . '&sname=' . $sname . '&saddress=' . $saddress . '&tphone=' . $tphone . '&tcat=' . $tcat . '&username=' . $genname . '&password=' . $genpassword . '">Authenticate Trader</a></button></strong>

                                                
                                                        </span>
                                                    </td>
                                                </tr>														</table>
													</td>
												</tr>
											</table>
											<p>This is a system generated email. Please do not reply to this email!
											</p>
											<p>Cheers,
												<br>Daily Bread Quintessential(DBQ MART)
												<div class="dblogo"><img src="cid:logo"/></div>

											</p>
											<!-- Sub copy -->

										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>
</body>

</html>
';
	return $html;
}










function send_invoice($cartArr, $ename, $total, $oid)
{
	$html = '
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="x-apple-disable-message-reformatting" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="css/fonts.css">
	<title></title>
	<style type="text/css" rel="stylesheet" media="all">
		/* Base ------------------------------ */

		@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");

		body {
			width: 100% !important;
			height: 100%;
			margin: 0;
			-webkit-text-size-adjust: none;
		}

		a {
			color: #3869D4;
		}

		a img {
			border: none;
		}

		td {
			word-break: break-word;
		}

		.preheader {
			display: none !important;
			visibility: hidden;
			font-size: 1px;
			line-height: 1px;
			max-height: 0;
			max-width: 0;
			opacity: 0;
			overflow: hidden;
		}

		/* Type ------------------------------ */

		body,
		td,
		th {
			font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
		}

		h1 {
			margin-top: 0;
			color: #333333;
			font-size: 22px;
			font-weight: bold;
			text-align: left;
		}
        .verify{
            padding:10px;
            margin:30px auto;
        }
        .verify a{
            text-decoration:none;
            font-size:18px;
        }

		h2 {
			margin-top: 0;
			color: #333333;
			font-size: 16px;
			font-weight: bold;
			text-align: left;
		}

		h3 {
			margin-top: 0;
			color: #333333;
			font-size: 14px;
			font-weight: bold;
			text-align: left;
		}

		td,
		th {
			font-size: 16px;
		}

		p,
		ul,
		ol,
		blockquote {
			margin: .4em 0 1.1875em;
			font-size: 16px;
			line-height: 1.625;
            text-align:justify;
		}

		p.sub {
			font-size: 13px;
		}

		/* Utilities ------------------------------ */

		.align-right {
			text-align: right;
		}

		.align-left {
			text-align: left;
		}

		.align-center {
			text-align: center;
		}

		/* Buttons ------------------------------ */

		.button {
			background-color: #3869D4;
			border-top: 10px solid #3869D4;
			border-right: 18px solid #3869D4;
			border-bottom: 10px solid #3869D4;
			border-left: 18px solid #3869D4;
			display: inline-block;
			color: #FFF;
			text-decoration: none;
			border-radius: 3px;
			box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
			-webkit-text-size-adjust: none;
			box-sizing: border-box;
		}

		.button--green {
			background-color: #22BC66;
			border-top: 10px solid #22BC66;
			border-right: 18px solid #22BC66;
			border-bottom: 10px solid #22BC66;
			border-left: 18px solid #22BC66;
		}

		.button--red {
			background-color: #FF6136;
			border-top: 10px solid #FF6136;
			border-right: 18px solid #FF6136;
			border-bottom: 10px solid #FF6136;
			border-left: 18px solid #FF6136;
		}

		@media only screen and (max-width: 500px) {
			.button {
				width: 100% !important;
				text-align: center !important;
			}
		}

		/* Attribute list ------------------------------ */

		.attributes {
			margin: 0 0 21px;
		}

		.attributes_content {
			background-color: #F4F4F7;
			padding: 16px;
		}

		.attributes_item {
			padding: 0;
		}

		/* Related Items ------------------------------ */

		.related {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.related_item {
			padding: 10px 0;
			color: #CBCCCF;
			font-size: 15px;
			line-height: 18px;
		}

		.related_item-title {
			display: block;
			margin: .5em 0 0;
		}

		.related_item-thumb {
			display: block;
			padding-bottom: 10px;
		}

		.related_heading {
			border-top: 1px solid #CBCCCF;
			text-align: center;
			padding: 25px 0 10px;
		}

		/* Discount Code ------------------------------ */

		.discount {
			width: 100%;
			margin: 0;
			padding: 24px;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
			border: 2px dashed #CBCCCF;
		}

		.discount_heading {
			text-align: center;
		}

		.discount_body {
			text-align: center;
			font-size: 15px;
		}

		/* Social Icons ------------------------------ */

		.social {
			width: auto;
		}

		.social td {
			padding: 0;
			width: auto;
		}

		.social_icon {
			height: 20px;
			margin: 0 8px 10px 8px;
			padding: 0;
		}
        .email-masthead td{
            width:50%;
            margin: 0 auto;
        }


		/* Data table ------------------------------ */

		.purchase {
			width: 100%;
			margin: 0;
			padding: 35px 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_content {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_item {
			padding: 10px 0;
			color: #51545E;
			font-size: 15px;
			line-height: 18px;
		}

		.purchase_heading {
			padding-bottom: 8px;
			border-bottom: 1px solid #EAEAEC;
		}

		.purchase_heading p {
			margin: 0;
			color: #85878E;
			font-size: 12px;
		}

		.purchase_footer {
			padding-top: 15px;
			border-top: 1px solid #EAEAEC;
		}

		.purchase_total {
			margin: 0;
			text-align: right;
			font-weight: bold;
			color: #333333;
		}

		.purchase_total--label {
			padding: 0 15px 0 0;
		}

		body {
			background-color: #F4F4F7;
			color: #51545E;
		}

		p {
			color: #51545E;
		}

		p.sub {
			color: #6B6E76;
		}

		.email-wrapper {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
		}

		.email-content {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		/* Masthead ----------------------- */

		.email-masthead {
			padding: 25px 0;
			text-align: center;
            background-color: #F2B34E;
		}

		.email-masthead_logo {
			width: 94px;
		}



		.email-masthead_name {
			font-size: 16px;
			font-weight: bold;
			color: black;
			text-decoration: none;
			text-shadow: 0 1px 0 white;
		}

		/* Body ------------------------------ */

		.email-body {
			width: 100%;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            
		}

		.email-body_inner {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
            border-radius:12px;
		}

		.email-footer {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.email-footer p {
			color: #6B6E76;
		}

		.body-action {
			width: 100%;
			margin: 30px auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.body-sub {
			margin-top: 25px;
			padding-top: 25px;
			border-top: 1px solid #EAEAEC;
		}

		.content-cell {
			padding: 35px;
		}


		.dblogo {
			width: 50%;


		}

		.dblogo img {

			width: 30vh;
			margin: 0 auto;
		}

		/*Media Queries ------------------------------ */

		@media only screen and (max-width: 600px) {

			.email-body_inner,
			.email-footer {
				width: 100% !important;
			}
		}

		@media (prefers-color-scheme: dark) {

			body,
			.email-body,
			.email-body_inner,
			.email-content,
			.email-wrapper,
			.email-masthead,
			.email-footer {
				background-color: #333333 !important;
				color: #FFF !important;
			}

			p,
			ul,
			ol,
			blockquote,
			h1,
			h2,
			h3 {
				color: #FFF !important;
			}

			.attributes_content,
			.discount {
				background-color: #222 !important;
			}

			.email-masthead_name {
				text-shadow: none !important;
			}
		}
	</style>
	<!--[if mso]>
		<style type="text/css">
		  .f-fallback  {
			font-family: Arial, sans-serif;
		  }
		</style>
	  <![endif]-->
</head>

<body>
	<span class="preheader">This is an invoice for your purchase on</span>
	<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
					<tr>
						<td class="email-masthead">

							<a href="" class="f-fallback email-masthead_name">
								Daily Bread Quintessential (DBQ MART)
							</a>
						</td>
					</tr>
					<!-- Email Body -->
					<tr>
						<td class="email-body" width="100%" cellpadding="0" cellspacing="0">
							<table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
								role="presentation">
								<!-- Body content -->
								<tr>
									<td class="content-cell">
										<div class="f-fallback">
											<h1>Hello ' . $ename . ',</h1>
											<p>This is an invoice for your recent purchase.</p>
											<table class="attributes" width="100%" cellpadding="0" cellspacing="0"
												role="presentation">
												<tr>
													<td class="attributes_content">
														<table width="100%" cellpadding="0" cellspacing="0"
															role="presentation">
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Amount Due: </strong>' . '£' . number_format($total, 2) . '
																	</span>
																</td>
															</tr>
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Order ID: </strong>' . $oid . '
																	</span>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<!-- Action -->

											<table class="purchase" width="100%" cellpadding="0" cellspacing="0">

												<tr>
													<td colspan="2">
														<table class="purchase_content" width="100%" cellpadding="0"
															cellspacing="0">
															<tr>
																<th class="purchase_heading" align="left">
																	<p class="f-fallback">Description</p>
																</th>
																<th class="purchase_heading" align="left">
																	<p class="f-fallback">Qty</p>
																</th>
																<th class="purchase_heading" align="right">
																	<p class="f-fallback">Amount</p>
																</th>
															</tr>';
	$ftotal = 0;
	foreach ($cartArr as $key => $list) {
		$itotal = $list['PRODUCT_QUANTITY'] * $list['PRODUCT_PRICE'];
		$ftotal = $ftotal + $itotal;
		$html .= '<tr>
															
																<td width="40%" class="purchase_item"><span
																		class="f-fallback">' . $list['PRODUCT_NAME'] . '</span></td>
																<td width="40%" class="purchase_item"><span
																		class="f-fallback">' . $list['PRODUCT_QUANTITY'] . '</span></td>
																<td class="align-right" width="20%"
																	class="purchase_item"><span
																		class="f-fallback">£' . number_format($list['PRODUCT_PRICE'] * $list['PRODUCT_QUANTITY'], 2) . '</span></td>
															</tr>';
	}

	$html .= '<tr>
																<td width="80%" class="purchase_footer" valign="middle"
																	colspan="2">
																	<p
																		class="f-fallback purchase_total purchase_total--label">
																		Total</p>
																</td>
																<td width="20%" class="purchase_footer" valign="middle">
																	<p class="f-fallback purchase_total">£
																		' . number_format($ftotal, 2) . '</p>
																</td>

															</tr>
														</table>
													</td>
												</tr>
											</table>
											<p>If you have any questions about this invoice, simply reply to this email
												or reach out to our <a href="mailto:dbqmart@gmail.com">support team</a> for help.
												Also bring a copy of this invoice during the collection.
											</p>
											<p>Cheers,
												<br>Daily Bread Quintessential(DBQ MART)

											</p>
											<div class="dblogo"><img src="cid:logo"/></div>
											<!-- Sub copy -->

										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>
</body>

</html>
';
	return ($html);
}



/**ORDER DETAILS RELATED FUNCTIONS */

function getUserOrder()
{
	include('connection.php');
	$arr = array();
	$id = $_SESSION['USER_ID'];
	$res = oci_parse($conn, "SELECT * FROM ORDERS WHERE CUSTOMER_ID='$id'");
	oci_execute($res);
	while ($row = oci_fetch_assoc($res)) {
		$arr[] = $row;
	}
	return $arr;
}
function getOrderDetails($oid)
{
	include('connection.php');
	$sql = "SELECT OP.UNIT_PRICE,OP.QUANTITY,P.PRODUCT_NAME,OP.FK1_PRODUCT_ID FROM ORDERS_PRODUCT OP, PRODUCT P WHERE OP.FK1_PRODUCT_ID=P.PRODUCT_ID AND OP.ORDER_ID='$oid'";
	$data = array();
	//prx($con);
	$res = oci_parse($conn, $sql);
	oci_execute($res);
	while ($row = oci_fetch_assoc($res)) {
		$data[] = $row;
	}
	return $data;
}

/** Send Mail For Order PAYMENT paid  */
function send_receipt($oid, $payment_gross, $payment_date, $rname, $rinvoice)
{
	$html = '
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="x-apple-disable-message-reformatting" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="css/fonts.css">
	<title></title>
	<style type="text/css" rel="stylesheet" media="all">
		/* Base ------------------------------ */

		@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");

		body {
			width: 100% !important;
			height: 100%;
			margin: 0;
			-webkit-text-size-adjust: none;
		}

		a {
			color: #3869D4;
		}

		a img {
			border: none;
		}

		td {
			word-break: break-word;
		}

		.preheader {
			display: none !important;
			visibility: hidden;
			font-size: 1px;
			line-height: 1px;
			max-height: 0;
			max-width: 0;
			opacity: 0;
			overflow: hidden;
		}

		/* Type ------------------------------ */

		body,
		td,
		th {
			font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
		}

		h1 {
			margin-top: 0;
			color: #333333;
			font-size: 22px;
			font-weight: bold;
			text-align: left;
		}
        .verify{
            padding:10px;
            margin:30px auto;
        }
        .verify a{
            text-decoration:none;
            font-size:18px;
        }

		h2 {
			margin-top: 0;
			color: #333333;
			font-size: 16px;
			font-weight: bold;
			text-align: left;
		}

		h3 {
			margin-top: 0;
			color: #333333;
			font-size: 14px;
			font-weight: bold;
			text-align: left;
		}

		td,
		th {
			font-size: 16px;
		}

		p,
		ul,
		ol,
		blockquote {
			margin: .4em 0 1.1875em;
			font-size: 16px;
			line-height: 1.625;
            text-align:justify;
		}

		p.sub {
			font-size: 13px;
		}

		/* Utilities ------------------------------ */

		.align-right {
			text-align: right;
		}

		.align-left {
			text-align: left;
		}

		.align-center {
			text-align: center;
		}

		/* Buttons ------------------------------ */

		.button {
			background-color: #3869D4;
			border-top: 10px solid #3869D4;
			border-right: 18px solid #3869D4;
			border-bottom: 10px solid #3869D4;
			border-left: 18px solid #3869D4;
			display: inline-block;
			color: #FFF;
			text-decoration: none;
			border-radius: 3px;
			box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
			-webkit-text-size-adjust: none;
			box-sizing: border-box;
		}

		.button--green {
			background-color: #22BC66;
			border-top: 10px solid #22BC66;
			border-right: 18px solid #22BC66;
			border-bottom: 10px solid #22BC66;
			border-left: 18px solid #22BC66;
		}

		.button--red {
			background-color: #FF6136;
			border-top: 10px solid #FF6136;
			border-right: 18px solid #FF6136;
			border-bottom: 10px solid #FF6136;
			border-left: 18px solid #FF6136;
		}

		@media only screen and (max-width: 500px) {
			.button {
				width: 100% !important;
				text-align: center !important;
			}
		}

		/* Attribute list ------------------------------ */

		.attributes {
			margin: 0 0 21px;
		}

		.attributes_content {
			background-color: #F4F4F7;
			padding: 16px;
		}

		.attributes_item {
			padding: 0;
		}

		/* Related Items ------------------------------ */

		.related {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.related_item {
			padding: 10px 0;
			color: #CBCCCF;
			font-size: 15px;
			line-height: 18px;
		}

		.related_item-title {
			display: block;
			margin: .5em 0 0;
		}

		.related_item-thumb {
			display: block;
			padding-bottom: 10px;
		}

		.related_heading {
			border-top: 1px solid #CBCCCF;
			text-align: center;
			padding: 25px 0 10px;
		}

		/* Discount Code ------------------------------ */

		.discount {
			width: 100%;
			margin: 0;
			padding: 24px;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
			border: 2px dashed #CBCCCF;
		}

		.discount_heading {
			text-align: center;
		}

		.discount_body {
			text-align: center;
			font-size: 15px;
		}

		/* Social Icons ------------------------------ */

		.social {
			width: auto;
		}

		.social td {
			padding: 0;
			width: auto;
		}

		.social_icon {
			height: 20px;
			margin: 0 8px 10px 8px;
			padding: 0;
		}
        .email-masthead td{
            width:50%;
            margin: 0 auto;
        }


		/* Data table ------------------------------ */

		.purchase {
			width: 100%;
			margin: 0;
			padding: 35px 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_content {
			width: 100%;
			margin: 0;
			padding: 25px 0 0 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		.purchase_item {
			padding: 10px 0;
			color: #51545E;
			font-size: 15px;
			line-height: 18px;
		}

		.purchase_heading {
			padding-bottom: 8px;
			border-bottom: 1px solid #EAEAEC;
		}

		.purchase_heading p {
			margin: 0;
			color: #85878E;
			font-size: 12px;
		}

		.purchase_footer {
			padding-top: 15px;
			border-top: 1px solid #EAEAEC;
		}

		.purchase_total {
			margin: 0;
			text-align: right;
			font-weight: bold;
			color: #333333;
		}

		.purchase_total--label {
			padding: 0 15px 0 0;
		}

		body {
			background-color: #F4F4F7;
			color: #51545E;
		}

		p {
			color: #51545E;
		}

		p.sub {
			color: #6B6E76;
		}

		.email-wrapper {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #F4F4F7;
		}

		.email-content {
			width: 100%;
			margin: 0;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
		}

		/* Masthead ----------------------- */

		.email-masthead {
			padding: 25px 0;
			text-align: center;
            background-color: #F2B34E;
		}

		.email-masthead_logo {
			width: 94px;
		}



		.email-masthead_name {
			font-size: 16px;
			font-weight: bold;
			color: black;
			text-decoration: none;
			text-shadow: 0 1px 0 white;
		}

		/* Body ------------------------------ */

		.email-body {
			width: 100%;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            
		}

		.email-body_inner {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			background-color: #FFFFFF;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
            border-radius:12px;
		}

		.email-footer {
			width: 570px;
			margin: 0 auto;
			padding: 0;
			-premailer-width: 570px;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.email-footer p {
			color: #6B6E76;
		}

		.body-action {
			width: 100%;
			margin: 30px auto;
			padding: 0;
			-premailer-width: 100%;
			-premailer-cellpadding: 0;
			-premailer-cellspacing: 0;
			text-align: center;
		}

		.body-sub {
			margin-top: 25px;
			padding-top: 25px;
			border-top: 1px solid #EAEAEC;
		}

		.content-cell {
			padding: 35px;
		}


		.dblogo {
			width: 50%;


		}

		.dblogo img {

			width: 30vh;
			margin: 0 auto;
		}

		/*Media Queries ------------------------------ */

		@media only screen and (max-width: 600px) {

			.email-body_inner,
			.email-footer {
				width: 100% !important;
			}
		}

		@media (prefers-color-scheme: dark) {

			body,
			.email-body,
			.email-body_inner,
			.email-content,
			.email-wrapper,
			.email-masthead,
			.email-footer {
				background-color: #333333 !important;
				color: #FFF !important;
			}

			p,
			ul,
			ol,
			blockquote,
			h1,
			h2,
			h3 {
				color: #FFF !important;
			}

			.attributes_content,
			.discount {
				background-color: #222 !important;
			}

			.email-masthead_name {
				text-shadow: none !important;
			}
		}
	</style>
	<!--[if mso]>
		<style type="text/css">
		  .f-fallback  {
			font-family: Arial, sans-serif;
		  }
		</style>
	  <![endif]-->
</head>

<body>
	<span class="preheader">This is a receipt for your recent order</span>
	<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
					<tr>
						<td class="email-masthead">

							<a href="" class="f-fallback email-masthead_name">
								Daily Bread Quintessential (DBQ MART)
							</a>
						</td>
					</tr>
					<!-- Email Body -->
					<tr>
						<td class="email-body" width="100%" cellpadding="0" cellspacing="0">
							<table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
								role="presentation">
								<!-- Body content -->
								<tr>
									<td class="content-cell">
										<div class="f-fallback">
										<h1>Hello ' . $rname . ',</h1>
											<p>This is a payment receipt for your recent purchase pertaining to the invoice sent earlier.</p>
											<table class="attributes" width="100%" cellpadding="0" cellspacing="0"
												role="presentation">
												<tr>
													<td class="attributes_content">
														<table width="100%" cellpadding="0" cellspacing="0"
															role="presentation">
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Amount Paid: </strong>' . '£' . number_format($payment_gross, 2) . '
																	</span>
																</td>
															</tr>
															<tr>
															<td class="attributes_item">
															<span class="f-fallback">
															<strong>Payment Date: </strong>' . $payment_date . '
														</span>
														</td>
														</tr>
														<tr>
														<td class="attributes_item">
														<span class="f-fallback">
														<strong>Payment Method: </strong>PayPal
													</span>
													</td>
													</tr>
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Order ID: </strong>' . $oid . '
																	</span>
																</td>
															</tr>
															<tr>
																<td class="attributes_item">
																	<span class="f-fallback">
																		<strong>Receipt Invoice: </strong>' . $rinvoice . '
																	</span>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<!-- Action -->

											<table class="purchase" width="100%" cellpadding="0" cellspacing="0">

												<tr>
													<td colspan="2">
														<table class="purchase_content" width="100%" cellpadding="0"
															cellspacing="0">
															<tr>
																<th class="purchase_heading" align="left">
																	<p class="f-fallback">Description</p>
																</th>
																<th class="purchase_heading" align="left">
																	<p class="f-fallback">Qty</p>
																</th>
																<th class="purchase_heading" align="right">
																	<p class="f-fallback">Amount</p>
																</th>
															</tr>';
	$orderArr = getOrderDetails($oid);
	$ftotal = 0;
	foreach ($orderArr as $key => $list) {
		$itotal = $list['QUANTITY'] * $list['UNIT_PRICE'];
		$ftotal = $ftotal + $itotal;
		$html .= '<tr>
															
																<td width="40%" class="purchase_item"><span
																		class="f-fallback">' . $list['PRODUCT_NAME'] . '</span></td>
																<td width="40%" class="purchase_item"><span
																		class="f-fallback">' . $list['QUANTITY'] . '</span></td>
																<td class="align-right" width="20%"
																	class="purchase_item"><span
																		class="f-fallback">£' . number_format($list['UNIT_PRICE'] * $list['QUANTITY'], 2) . '</span></td>
															</tr>';
	}

	$html .= '<tr>
																<td width="80%" class="purchase_footer" valign="middle"
																	colspan="2">
																	<p
																		class="f-fallback purchase_total purchase_total--label">
																		Total</p>
																</td>
																<td width="20%" class="purchase_footer" valign="middle">
																	<p class="f-fallback purchase_total">£
																		' . number_format($ftotal, 2) . '</p>
																</td>

															</tr>
														</table>
													</td>
												</tr>
											</table>
											<p>If you have any questions about this payment receipt, simply reply to this email
												or reach out to our <a href="mailto:dbqmart@gmail.com">support team</a> for help.
												Also bring a copy of this receipt during the collection at the collection centre which you confirmed through the call received.
											</p>
											<p>Cheers,
												<br>Daily Bread Quintessential(DBQ MART)

											</p>
											<div class="dblogo"><img src="cid:logo"/></div>
											<!-- Sub copy -->

										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>
</body>

</html>
';
	return ($html);
}

?>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
	<!-- Paypal business test account email id so that you can collect the payments. -->
	<input type="hidden" name="business" value="sb-ivxgi6666870@business.example.com">
	<!-- Buy Now button. -->
	<input type="hidden" name="cmd" value="_cart">
	<!---xclick--->
	<input type="hidden" name="upload" value="1">
	<!-- Details about the item that buyers will purchase. -->
	<input type="hidden" name="item_name_1" value="bread">
	<input type="hidden" name="item_number_1" value="1">
	<input type="hidden" name="amount_1" value="5">


	<input type="hidden" name="item_name_2" value="cake">
	<input type="hidden" name="item_number_2" value="2">
	<input type="hidden" name="amount_2" value="10">


	<input type="hidden" name="currency_code" value="GBP">
	<input type="hidden" name="rm" value="2">
	<!-- URLs -->
	<input type='hidden' name='cancel_return' value='http://localhost/paypal_integration_php/cancel.php'>
	<input type='hidden' name='return' value='http://localhost/management/sandbox/success.php'>
	<!-- payment button. -->
	<input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
	<img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
</form>
<?PHP 
  include('connection.php');
  $user_id = $_SESSION['TRADER_ID'];
?>
<div class="addproductspage_elements">
  <div class="addproductspage_title">
    <h1> Hello Dear, You can Add Your Products Here.</h1>
  </div>
  <form method="POST" enctype="multipart/form-data">
    <div class="addproductspage_element">
      <label>Product Name</label>
      <input type="text" name="ProductName">
    </div>
    <div class="addproductspage_element">
      <label>Product Price</label>
      <input type="number" name="ProductPrice">
    </div>
    <div class="addproductspage_element">
      <label>Product Stock</label>
      <input type="number" name="ProductStock">
    </div>
    <div class="addproductspage_element">
      <label>Product Detail</label>
      <input type="text" name="ProductDetail">
    </div>
    <div class="addproductspage_element">
      <label>Maximum Order</label>
      <input type="number" name="MaximumOrder">
    </div>
    <div class="addproductspage_element">
      <label>Minimum Order</label>
      <input type="number" name="MinimumOrder">
    </div>
    <div class="addproductspage_element">
      <label>Allergy Information</label>
      <input type="text" name="AllergyInformation">
    </div>
    <div class="addproductspage_element">
      <label for="shops">Choose a Shop:</label>
      <?PHP
          $qry = oci_parse($conn, "SELECT * FROM SHOP WHERE FK1_USER_ID = '$user_id'");
          oci_execute($qry);?>
      <select name="shops" id="shops">
       <?php
          while($row = oci_fetch_assoc($qry)){
        ?>
        <option value="<?PHP echo $row['SHOP_NAME']?>"><?PHP echo $row['SHOP_NAME']?></option>
        <?PHP
          }
        ?>
      </select>
    </div>
    <div class="addproductspage_element">
      <label for="product__type">Choose Product Type:</label>
      <?PHP
          $qry = oci_parse($conn, "SELECT * FROM PRODUCT_TYPE");
          oci_execute($qry);?>
      <select name="product__type" id="product__type">
       <?php
          while($row = oci_fetch_assoc($qry)){
        ?>
        <option value="<?PHP echo $row['PRODUCT_TYPE_NAME']?>"><?PHP echo $row['PRODUCT_TYPE_NAME']?></option>
        <?PHP
          }
        ?>
      </select>
    </div>
    <div class="addproductspage_element">
      <label>Product Image</label>
      <input type="file" id="myFile" name="file">
    </div>
    <div class="addproductspage_element">
      <label>Product Status</label>
      <input type="number" name="ProductStatus">
    </div>
    <div class="addproductspage_element">
      <label>Old Price</label>
      <input type="number" name="ProductOldPrice">
    </div>
    <div class="addproductspage_element">
      <label>Long Description</label>
      <textarea id="mytextarea" name="ProductLongDesc" required>Hello, World!</textarea>
    </div>
    <input type="submit" name="addproduct" value="Add Product">
  </form>
</div>

<?PHP 

if(isset($_POST['addproduct'])){

  $filepath = "./images/products/" . $_FILES["file"]["name"];
  if(move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) 
  {
    echo "<img src=".$filepath." height=200 width=300 />";
  } 
  else 
  {
    echo "Error Uploading File Contact Developer!!";
  }



  $product_name = $_POST['ProductName'];
  $product_price = $_POST['ProductPrice'];
  $product_stock = $_POST['ProductStock'];
  $product_detail = $_POST['ProductDetail'];
  $maximum_order = $_POST['MaximumOrder'];
  $minimum_order = $_POST['MinimumOrder'];
  $allergy_information = $_POST['AllergyInformation'];
  $product_status = $_POST['ProductStatus'];
  $old_price = $_POST['ProductOldPrice'];
  $long_desc = $_POST['ProductLongDesc'];
  $product_image = $_FILES['file']['name'];
  $shop_name = $_POST['shops'];
  $product_type = $_POST['product__type'];



  $qry = oci_parse($conn, "SELECT * FROM SHOP WHERE SHOP_NAME = '$shop_name'");
  oci_execute($qry);

  $row = oci_fetch_assoc($qry);
  $shop_id = $row['SHOP_ID'];

  $qry = oci_parse($conn, "SELECT PRODUCT_TYPE_ID FROM PRODUCT_TYPE WHERE PRODUCT_TYPE_NAME = '$product_type'");
  oci_execute($qry);

  $row = oci_fetch_assoc($qry);
  $product_type_id = $row['PRODUCT_TYPE_ID'];


  $qry = oci_parse($conn, "INSERT INTO PRODUCT(PRODUCT_NAME, PRODUCT_PRICE, PRODUCT_STOCK, PRODUCT_DETAIL, MAXIMUM_ORDER, MINIMUM_ORDER, ALLERGY_INFORMATION, FK1_SHOP_ID, FK2_PRODUCT_TYPE_ID, PRODUCT_IMAGE, STATUS, OLD_PRICE, LONG_DESC) 
  VALUES('$product_name', '$product_price', '$product_stock', '$product_detail', '$maximum_order', '$minimum_order', '$allergy_information', '$shop_id', '$product_type_id', '$product_image' ,'$product_status', '$old_price', '$long_desc')");

  oci_execute($qry);  


}




?>
<?PHP
$product_id = $_GET['productid'];
$user_id = $_SESSION["TRADER_ID"];
include('./connection.php');

$sql = oci_parse($conn, "SELECT * FROM PRODUCT WHERE PRODUCT_ID = '$product_id'");
oci_execute($sql);
while ($row = oci_fetch_array($sql)) {
?>

  <div class="traderpage-editproduct-elements">
    <form method="POST" enctype="multipart/form-data">
      <div class="traderpage-editproduct-element">
        <label>Product_Name</label>
        <input type="text" value="<?PHP echo $row['PRODUCT_NAME'] ?>" name="PRODUCT_NAME">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Product_Price</label>
        <input type="text" value="<?PHP echo $row['PRODUCT_PRICE'] ?>" name="PRODUCT_PRICE">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Product_Stock</label>
        <input type="text" value="<?PHP echo $row['PRODUCT_STOCK'] ?>" name="PRODUCT_STOCK">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Product_Detail</label>
        <input type="text" value="<?PHP echo $row['PRODUCT_DETAIL'] ?>" name="PRODUCT_DETAIL">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Maximum Order</label>
        <input type="text" value="<?PHP echo $row['MAXIMUM_ORDER'] ?>" name="MAXIMUM_ORDER">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Minimum Order</label>
        <input type="text" value="<?PHP echo $row['MINIMUM_ORDER'] ?>" name="MINIMUM_ORDER">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Allergy Information</label>
        <input type="text" value="<?PHP echo $row['ALLERGY_INFORMATION'] ?>" name="ALLERGY_INFORMATION">
      </div>
      <div class="addproductspage_element">
        <label>Product in this shop currently:
          <?PHP
          $qry = oci_parse($conn, "SELECT FK1_SHOP_ID FROM PRODUCT WHERE PRODUCT_ID = '$product_id'");
          oci_execute($qry);
          $result = oci_fetch_assoc($qry);
          $shopid = $result['FK1_SHOP_ID'];
          $qry = oci_parse($conn, "SELECT SHOP_NAME FROM SHOP WHERE SHOP_ID = '$shopid'");
          oci_execute($qry);
          $result = oci_fetch_assoc($qry);
          echo $result['SHOP_NAME'];
          ?>
        </label>
        <?PHP
        $qry = oci_parse($conn, "SELECT * FROM SHOP WHERE FK1_USER_ID = '$user_id'");
        oci_execute($qry); ?>
        <select name="shops" id="shops">
          <?php
          while ($result = oci_fetch_assoc($qry)) {
          ?>
            <option value="<?PHP echo $result['SHOP_ID'] ?>"><?PHP echo $result['SHOP_NAME'] ?></option>
          <?PHP
          }
          ?>
        </select>
      </div>
      <div class="addproductspage_element">
        <label for="product__type">Current Product Type:
          <?PHP
          $qry = oci_parse($conn, "SELECT * FROM PRODUCT WHERE PRODUCT_ID = '$product_id'");
          oci_execute($qry);
          $result = oci_fetch_assoc($qry);
          $product_type_id = $result['FK2_PRODUCT_TYPE_ID'];

          $qry = oci_parse($conn, "SELECT PRODUCT_TYPE_NAME FROM PRODUCT_TYPE WHERE PRODUCT_TYPE_ID = '$product_type_id'");
          oci_execute($qry);
          $result = oci_fetch_assoc($qry);
          echo $result['PRODUCT_TYPE_NAME'];
          ?>
        </label>
        <?PHP
        $qry = oci_parse($conn, "SELECT * FROM PRODUCT_TYPE");
        oci_execute($qry); ?>
        <select name="product__type" id="product__type">
          <?php
          while ($result = oci_fetch_assoc($qry)) {
          ?>
            <option value="<?PHP echo $result['PRODUCT_TYPE_ID'] ?>"><?PHP echo $result['PRODUCT_TYPE_NAME'] ?></option>
          <?PHP
          }
          ?>
        </select>
      </div>
      <div class="addproductspage_element">
        <label>Product Image</label>
        <?PHP
        $qry = oci_parse($conn, "SELECT * FROM PRODUCT WHERE PRODUCT_ID = '$product_id'");
        oci_execute($qry);
        $result = oci_fetch_assoc($qry);
        ?>
        <img src="./images/products/<?PHP echo $result['PRODUCT_IMAGE'] ?>" alt="" style="width:100px;height:100px;">
        <input type="file" id="myFile" name="file">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Product Status</label>
        <input type="text" value="<?PHP echo $row['STATUS'] ?>" name="STATUS">
      </div>
      <div class="traderpage-editproduct-element">
        <label>OLD_PRICE</label>
        <input type="text" value="<?PHP echo $row['OLD_PRICE'] ?>" name="OLD_PRICE">
      </div>
      <div class="traderpage-editproduct-element">
        <label>Long Description</label>
        <textarea id="mytextarea" name="LONG_DESC" required><?PHP echo $row['LONG_DESC'] ?></textarea>
      </div>
      <input type="submit" name="editproduct" value="Edit Product">
    </form>
  </div>

<?PHP
}

if (isset($_POST['editproduct'])) {
  $product_id = $_GET['productid'];
  $product_name = $_POST['PRODUCT_NAME'];
  $product_price = $_POST['PRODUCT_PRICE'];
  $product_stock = $_POST['PRODUCT_STOCK'];
  $product_detail = $_POST['PRODUCT_DETAIL'];
  $maximum_order = $_POST['MAXIMUM_ORDER'];
  $minimum_order = $_POST['MINIMUM_ORDER'];
  $allergy_information = $_POST['ALLERGY_INFORMATION'];
  $product_status = $_POST['STATUS'];
  $old_price = $_POST['OLD_PRICE'];
  $long_desc = $_POST['LONG_DESC'];
  $shop_ID = $_POST['shops'];
  $product_type = $_POST['product__type'];



  $filepath = "./images/products/" . $_FILES["file"]["name"];
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) {
  } else {
  }

  $product_image = $_FILES['file']['name'];
  if (empty($product_image)) {
    $sql = oci_parse($conn, "UPDATE PRODUCT SET PRODUCT_NAME = '$product_name', PRODUCT_PRICE = '$product_price', PRODUCT_STOCK = '$product_stock', PRODUCT_DETAIL = '$product_detail',  MAXIMUM_ORDER = '$maximum_order',  MINIMUM_ORDER = '$minimum_order',   ALLERGY_INFORMATION = '$allergy_information',  FK1_SHOP_ID = '$shop_ID',  FK2_PRODUCT_TYPE_ID = '$product_type', STATUS = '$product_status',  OLD_PRICE = '$old_price',  LONG_DESC = '$long_desc' WHERE  PRODUCT_ID = '$product_id'");
  } else {
    $sql = oci_parse($conn, "UPDATE PRODUCT SET PRODUCT_NAME = '$product_name', PRODUCT_PRICE = '$product_price', PRODUCT_STOCK = '$product_stock', PRODUCT_DETAIL = '$product_detail',  MAXIMUM_ORDER = '$maximum_order',  MINIMUM_ORDER = '$minimum_order',   ALLERGY_INFORMATION = '$allergy_information',  FK1_SHOP_ID = '$shop_ID',  FK2_PRODUCT_TYPE_ID = '$product_type',  PRODUCT_IMAGE = '$product_image', STATUS = '$product_status',  OLD_PRICE = '$old_price',  LONG_DESC = '$long_desc' WHERE  PRODUCT_ID = '$product_id'");
  }
  oci_execute($sql);
}
?>
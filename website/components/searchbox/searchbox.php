<?php
$name = '';
if (isset($_POST['search'])) {
  $name = trim($_POST['search']);
  $name = strtoupper($name);
  header("Location:./products.php?query=$name");
} else {
}

?>


<div id="myOverlay" class="overlay">
  <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
  <div class="overlay-content">
    <form action="" method='post'>
      <input type="text" placeholder="Search.." name="search">
      <button type="submit"><i class='bx bx-search-alt'></i></button>
    </form>
  </div>
</div>
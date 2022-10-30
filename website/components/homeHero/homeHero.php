<section id="hero">
  <div class="container">
    <?php
    include('./connection.php');
    $sql = oci_parse($conn, "SELECT * FROM BANNER");
    oci_execute($sql);
    while ($row = oci_fetch_array($sql)) {
      $img = $row['HERO-IMAGE'];
      $heading = $row['HEADING'];
      $tagline = $row['TAGLINE'];
      $price = $row['PRICE_DETAIL'];

    ?>
      <div class="hero hero-1">
        <div class="hero-image">
          <img src="./images/<?php echo $img; ?>" alt="banner">
          <div class="text">
            <h1 class="heading"><?php echo $heading; ?></h1>
            <p class="tagline"><?php echo $tagline; ?><span class="price">£<?php echo $price; ?></span></p>
            <a href='./products.php'><button class="btn">VIEW</button></a>
          </div>
        </div>
      </div>
    <?php } ?>
    <div class="arrows">
      <div class="left-arrow" onclick="plusSlides(-1)"><i class='bx bx-chevron-left'></i></div>
      <div class="right-arrow" onclick="plusSlides(1)"><i class='bx bx-chevron-right'></i></div>
    </div>
  </div>
</section>
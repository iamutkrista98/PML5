<section id="hero">
    <div class="container">
        <div class="abouthead">
            <h1 class='abtheading'>About Us</h1>

            <h2 class="aboutinfo">
                <p>Daily Bread Quintessential (DBQ MART) is the pioneer in ecommerce platform at Cleckhuddersfax. With the motto '
                    Quality at its Epitome', we excel in delivering quality produce from a range of local traders. The suburb of Cleckhuddersfax is perceived by its richness in culture and local businesses operated by independent traders. A group of them have come up with the idea to partake in the contemporary act of marketing their products online through an eCommerce platform. The system will conglomerate the products they sell, extending its availability which in turn will allow them to reach out to more customers. The modus operandi entirely is hinged on taking in orders from shoppers and setting up collection points for the distribution. The sole purpose of the undertaking is to extend their business opening hours abating any repercussions made on their personal lives.

                    The residents, mostly busy during normal opening hours, would be benefitted from the system in purchasing fresh products from the local traders of their choosing. The traders since the inception of their businesses have emphasized delivering quality products unmatched by any of the supermarkets in the locality.

                    At the initial phase, the traders have set their sights to sustain the triumph of their business with the availability of a platform to sell their goods 24/7. They strongly believe that an online platform would bestow them with remarkable attainments.
                </p>
                <div class='stamp'><img src='./images/transparent-logo.png'></div>
            </h2>
        </div>
    </div>
    <!--Our Team Section!-->
    <h2 class="teamheading">Our Team</h2>
    <section id="ourteam">
        <div class="teamcard">
            <div class="bg-image">
                <img src="images/hero-1.jpg" alt="card-background" title="card-background">
            </div>
            <div class="pic">
                <img src="images\rohan.jpg" alt="CEO" title="CEO">
            </div>
            <div class="teaminfo">
                <h3>Rohan Poudel</h3>
                <span>CEO</span>
            </div>
        </div>
        <div class="teamcard">
            <div class="bg-image">
                <img src="images\hero-1.jpg" alt="card-background" title="card-background">
            </div>
            <div class="pic">
                <img src="images\utkrista.jpg" alt="DCEO" title="DCEO">
            </div>
            <div class="teaminfo">
                <h3>Utkrista Acharya</h3>
                <span>DCEO</span>
            </div>
        </div>
        <div class="teamcard">
            <div class="bg-image">
                <img src="images\hero-1.jpg" alt="card-background" title="card-background">
            </div>
            <div class="pic">
                <img src="images/aashish.jpg" alt="Partner" title="Partner">
            </div>
            <div class="teaminfo">
                <h3>Aashish Kumar Jha</h3>
                <span>Partner</span>
            </div>
        </div>
        <div class="teamcard">
            <div class="bg-image">
                <img src="images/hero-1.jpg" alt="card-background" title="card-background">
            </div>
            <div class="pic">
                <img src="images/shiva.jpg" alt="Partner" title="Partner">
            </div>
            <div class="teaminfo">
                <h3>Shiva Marasini</h3>
                <span>Partner</span>
            </div>
        </div>
        <div class="teamcard">
            <div class="bg-image">
                <img src="images/hero-1.jpg" alt="card-background" title="card-background">
            </div>
            <div class="pic">
                <img src="images/mamta.jpg" alt="Partner" title="Partner">
            </div>
            <div class="teaminfo">
                <h3>Mamta K.C.</h3>
                <span>Partner</span>
            </div>
        </div>
    </section>
    <!--Our Team Section!-->
    <h2 class="teamheading">Pilot Traders</h2>
    <section id="ourteam">
        <?php
        include('./connection.php');
        $sql = oci_parse($conn, "SELECT * FROM TRADER");
        oci_execute($sql);
        while ($row = oci_fetch_array($sql)) {

        ?>
            <div class="teamcard">
                <div class="bg-image">
                    <img src="images/bgimage.jpg" alt="card-background" title="card-background">
                </div>
                <div class="pic">
                    <img src="images\traders\<?php echo $row['PROFILE_IMAGE']; ?>" alt="<?php echo $row['PROFILE_IMAGE']; ?>" title="">
                </div>
                <div class="teaminfo">
                    <h3><?php echo $row['TRADER_NAME']; ?></h3>
                    <?php echo '<span>' . 'Since<br>' . $row['ESTD'] . '</span>'; ?>
                </div>
            </div>
        <?php } ?>

    </section>


</section>
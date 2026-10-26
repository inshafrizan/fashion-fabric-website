<?php
require_once 'check_session.php';
?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="fabric, wholesale fabric, about us, cotton, silk, linen, Kandy, Sri Lanka" />
  <meta name="description" content="Learn about Fashion Fabric - Your trusted wholesale fabric partner in Kandy, Sri Lanka" />
  <meta name="author" content="Fashion Fabric" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title>About Us - Fashion Fabric</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
      <img src="images/hero-bg.jpg" alt="Fabric Background">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <?php include 'navbar.php'; ?>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- about section -->
  <section class="about_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="img-box">
            <img src="images/about-fabric.jpg" alt="Fashion Fabric Store">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                We Are Fashion Fabric
              </h2>
            </div>
            <p>
              <strong>Fashion Fabric</strong> is a wholesale fabric business situated in Udathalawinna, Kandy. Founded and owned by <strong>Mr. M. R. M. Riyad</strong>, we have been serving the local community and businesses for over two years with premium quality raw fabric materials.
            </p>
            <p>
              Our mission is to support local fashion creators, dressmakers, small garment factories, and designers by providing them with high-quality fabrics at competitive wholesale prices. We understand the needs of creators and strive to be the most reliable fabric supplier in the region.
            </p>
            <p>
              With a wide selection including cotton, silk, linen, net, and printed fabrics, we cater to diverse creative needs while maintaining the highest standards of quality and service.
            </p>
            <a href="fabrics.php">
              View Our Fabrics
            </a>
            <?php if(!isLoggedIn()): ?>
              <a href="register.php" class="btn" style="background: #2C5530; color: white; margin-left: 10px;">
                Join Our Community
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end about section -->

  <!-- story section -->
  <section class="story_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="story_detail">
            <h3>
              Our Story
            </h3>
            <p>
              What started as a small fabric shop in Udathalawinna has grown into a trusted wholesale supplier serving creators across Kandy and beyond. For over two years, we've built relationships with local dressmakers, garment factories, and individual creators who rely on us for consistent quality and reliable service.
            </p>
            <p>
              Our journey began with a simple vision: to make quality fabrics accessible to everyone in the fashion industry. Today, we continue that mission by expanding our collection and maintaining our commitment to customer satisfaction.
            </p>
            <div class="story_stats">
              <div class="stat_item">
                <h4>2+</h4>
                <p>Years Experience</p>
              </div>
              <div class="stat_item">
                <h4>500+</h4>
                <p>Happy Customers</p>
              </div>
              <div class="stat_item">
                <h4>50+</h4>
                <p>Fabric Varieties</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="story_img-box">
            <img src="images/fabric-store.jpg" alt="Our Fabric Store">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end story section -->

  <!-- values section -->
  <section class="values_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Our Values
        </h2>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="value_box text-center">
            <div class="value_icon">
              <i class="fa fa-star" aria-hidden="true"></i>
            </div>
            <h5>
              Quality Assurance
            </h5>
            <p>
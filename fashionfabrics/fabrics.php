<?php
require_once 'check_session.php';
require_once 'config/database.php';
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
  <meta name="keywords" content="fabric, wholesale fabric, cotton, silk, linen, Kandy, Sri Lanka" />
  <meta name="description" content="Fashion Fabric - Premium wholesale fabrics in Kandy, Sri Lanka" />
  <meta name="author" content="Fashion Fabric" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title>Our Fabrics - Fashion Fabric</title>

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

  <style>
    /* Filter styles */
    .filters_menu {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      list-style: none;
      padding: 0;
      margin: 30px 0;
    }
    
    .filters_menu li {
      padding: 10px 25px;
      cursor: pointer;
      border-radius: 30px;
      margin: 0 5px 10px;
      transition: all 0.3s;
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      font-weight: 500;
      color: #495057;
    }
    
    .filters_menu li:hover {
      background: #e9ecef;
      transform: translateY(-2px);
    }
    
    .filters_menu li.active {
      background: #2C5530;
      color: white;
      border-color: #2C5530;
    }
    
    /* Grid items */
    .grid {
      display: flex;
      flex-wrap: wrap;
      transition: all 0.3s ease;
    }
    
    .all {
      transition: all 0.4s ease;
      animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    /* Box styling */
    .box {
      margin-bottom: 30px;
      height: 100%;
      transition: all 0.3s ease;
    }
    
    .box:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    /* Image styling */
    .img-box img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 8px 8px 0 0;
    }
    
    /* Empty state styling */
    .empty-state {
      text-align: center;
      padding: 50px 20px;
      width: 100%;
    }
    
    .empty-state i {
      font-size: 64px;
      color: #ddd;
      margin-bottom: 20px;
    }
    
    .empty-state h4 {
      color: #666;
      margin-bottom: 15px;
    }
    
    .empty-state p {
      color: #888;
      margin-bottom: 25px;
    }
  </style>
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

  <!-- fabric section -->

  <section class="food_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Our Fabric Collection
        </h2>
        <p>
          Browse our premium selection of wholesale fabrics. All materials available in bulk quantities.
        </p>
        <?php if(isLoggedIn()): ?>
          <p class="text-center" style="color: #2C5530; font-style: italic;">
            <i class="fa fa-star" aria-hidden="true"></i> Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! Enjoy our exclusive fabric collection.
          </p>
        <?php endif; ?>
      </div>

      <ul class="filters_menu">
        <li class="active" data-filter="*">All Fabrics</li>
        <li data-filter=".cotton">Cotton</li>
        <li data-filter=".silk">Silk</li>
        <li data-filter=".linen">Linen</li>
        <li data-filter=".net">Net</li>
        <li data-filter=".printed">Printed</li>
      </ul>

      <div class="filters-content">
        <div class="row grid">
          <?php
          // Get all fabrics from database
          $sql = "SELECT * FROM fabrics WHERE status = 'available' ORDER BY id DESC";
          $result = $conn->query($sql);
          
          if ($result->num_rows > 0) {
              // SHOW ONLY DATABASE FABRICS
              while ($fabric = $result->fetch_assoc()) {
                  // Determine CSS class based on fabric type
                  $type_class = strtolower($fabric['type']);
                  
                  // Check if fabric is printed based on name or description
                  $is_printed = false;
                  $fabric_name = strtolower($fabric['name']);
                  $fabric_description = strtolower($fabric['description']);
                  
                  // Check if fabric name or description contains "print" or "printed"
                  if (strpos($fabric_name, 'print') !== false || 
                      strpos($fabric_description, 'print') !== false ||
                      $fabric['type'] == 'printed') {
                      $is_printed = true;
                  }
                  
                  // Handle image path
                  $image_path = '';
                  
                  // Case 1: If image_url is stored in database
                  if (!empty($fabric['image_url'])) {
                      $image_url = $fabric['image_url'];
                      
                      // If it's a full path from uploads folder
                      if (strpos($image_url, 'uploads/') !== false) {
                          // Extract just the filename
                          $filename = basename($image_url);
                          $image_path = 'images/' . $filename;
                      } 
                      // If it's just a filename
                      else {
                          $image_path = 'images/' . $image_url;
                      }
                  }
                  
                  // Case 2: If no image_url, use default based on type
                  else {
                      $type = strtolower($fabric['type']);
                      $possible_images = [
                          'cotton' => ['cotton1.jpg', 'cotton2.jpg', 'cotton3.jpg', 'cotton-offer.jpg'],
                          'silk' => ['silk1.jpg', 'silk2.jpg', 'silk-offer.jpg'],
                          'linen' => ['linen1.jpg', 'linen2.jpg'],
                          'net' => ['net1.jpg', 'net2.jpg'],
                          'printed' => ['cotton2.jpg', 'silk2.jpg']
                      ];
                      
                      if (isset($possible_images[$type])) {
                          foreach ($possible_images[$type] as $img) {
                              if (file_exists('images/' . $img)) {
                                  $image_path = 'images/' . $img;
                                  break;
                              }
                          }
                      }
                      
                      // Default image if none found
                      if (empty($image_path)) {
                          $image_path = 'images/default-fabric.jpg';
                      }
                  }
                  
                  // Verify the image exists
                  if (!file_exists($image_path) && !file_exists('../' . $image_path)) {
                      $image_path = 'images/default-fabric.jpg';
                  }
                  
                  // Get description or use default
                  $description = !empty($fabric['description']) 
                      ? $fabric['description'] 
                      : 'Premium quality ' . $fabric['type'] . ' fabric perfect for various clothing creations.';
                  
                  // Format price
                  $price = number_format($fabric['price_per_meter'], 2);
                  
                  // Generate fabric item with proper classes
                  $css_classes = 'all ' . $type_class;
                  if ($is_printed) {
                      $css_classes .= ' printed';
                  }
                  ?>
                  <div class="col-sm-6 col-lg-4 <?php echo $css_classes; ?>">
                    <div class="box">
                      <div>
                        <div class="img-box">
                          <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($fabric['name']); ?>" onerror="this.src='images/default-fabric.jpg'">
                        </div>
                        <div class="detail-box">
                          <h5>
                            <?php echo htmlspecialchars($fabric['name']); ?>
                          </h5>
                          <p>
                            <?php echo htmlspecialchars($description); ?>
                          </p>
                          <div class="options">
                            <h6>
                              LKR <?php echo $price; ?>/m
                            </h6>
                            <small style="display:block; font-size:12px; color:#666;">
                              Available: <?php echo $fabric['available_meters']; ?>m
                            </small>
                            <a href="order.php?fabric_id=<?php echo $fabric['id']; ?>">
                              Request Quote
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
              }
          } else {
              // NO FABRICS IN DATABASE - show empty state
              ?>
              <div class="col-12">
                <div class="empty-state">
                  <i class="fa fa-inbox" aria-hidden="true"></i>
                  <h4>No Fabrics Available</h4>
                  <p>Our fabric collection is currently being updated. Please check back soon!</p>
                  <p>In the meantime, you can <a href="order.php" style="color: #2C5530; font-weight: bold;">request a custom fabric quote</a>.</p>
                  <?php if(isAdmin()): ?>
                    <p><a href="admin/manage_fabrics.php" style="color: #2C5530; font-weight: bold;">Add fabrics through admin panel</a></p>
                  <?php endif; ?>
                </div>
              </div>
              <?php
          }
          $conn->close();
          ?>
        </div>
      </div>
    </div>
  </section>

  <!-- end fabric section -->

  <!-- why us section -->
  <section class="why_us_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Why Choose Fashion Fabric?
        </h2>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="box ">
            <div class="img-box">
              <i class="fa fa-trophy" aria-hidden="true"></i>
            </div>
            <div class="detail-box">
              <h5>
                Premium Quality
              </h5>
              <p>
                We source only the highest quality fabrics to ensure your creations look and feel exceptional.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box ">
            <div class="img-box">
              <i class="fa fa-balance-scale" aria-hidden="true"></i>
            </div>
            <div class="detail-box">
              <h5>
                Wholesale Prices
              </h5>
              <p>
                Competitive pricing for bulk orders, making us the preferred choice for businesses and creators.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box ">
            <div class="img-box">
              <i class="fa fa-truck" aria-hidden="true"></i>
            </div>
            <div class="detail-box">
              <h5>
                Reliable Supply
              </h5>
              <p>
                Consistent stock availability and reliable delivery for all your fabric needs.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end why us section -->

  <!-- footer section -->
  <?php include 'footer.php'; ?>
  <!-- footer section -->

  <!-- jQery -->
  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->

  <!-- Filter Functionality JavaScript -->
  <script>
    $(document).ready(function() {
      // Simple filter functionality
      $('.filters_menu li').on('click', function() {
        // Get filter value
        var filterValue = $(this).data('filter');
        
        // Update active class
        $('.filters_menu li').removeClass('active');
        $(this).addClass('active');
        
        // Show/hide items
        if (filterValue === '*') {
          $('.all').show();
        } else {
          // Hide all first
          $('.all').hide();
          // Show matching items
          $('.all').each(function() {
            if ($(this).hasClass(filterValue.replace('.', ''))) {
              $(this).show();
            }
          });
        }
      });
      
      // Initialize nice select if available
      if (typeof $.fn.niceSelect !== 'undefined') {
        $('.nice-select').niceSelect();
      }
    });
  </script>
</body>

</html>
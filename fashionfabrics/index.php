<?php
require_once 'check_session.php';
require_once 'config/database.php'; // Added database connection
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

  <title>Fashion Fabric - Premium Wholesale Fabrics</title>

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
    /* User link styles */
    .user_link {
      display: flex;
      align-items: center;
      color: white;
      text-decoration: none;
      padding: 5px 15px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
      transition: all 0.3s;
    }
    
    .user_link:hover {
      background: rgba(255, 255, 255, 0.2);
      text-decoration: none;
      color: white;
    }
    
    .user_link i {
      font-size: 18px;
      margin-right: 5px;
    }
    
    .user_link span {
      font-size: 14px;
    }
    
    /* Welcome message for logged-in users */
    .welcome-message {
      background: rgba(44, 85, 48, 0.1);
      padding: 10px 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      border-left: 4px solid #2C5530;
    }
    
    .welcome-message i {
      color: #2C5530;
      margin-right: 8px;
    }
    
    /* Remove cart and search styles */
    .cart_link, .nav_search-btn {
      display: none !important;
    }
    
    .order_online {
      display: inline-block;
      padding: 8px 20px;
      background: #ffbe33;
      color: white;
      border-radius: 45px;
      text-decoration: none;
      font-weight: 500;
      margin-left: 15px;
      transition: all 0.3s;
    }
    
    .order_online:hover {
      background: #e69c00;
      color: white;
      text-decoration: none;
    }
    
    /* Dropdown menu styles */
    .dropdown {
      position: relative;
      display: inline-block;
    }
    
    .dropdown-menu {
      display: none;
      position: absolute;
      background: white;
      min-width: 180px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      z-index: 1000;
      border-radius: 5px;
      padding: 10px 0;
      right: 0;
      left: auto;
      margin-top: 10px;
    }
    
    .dropdown:hover .dropdown-menu {
      display: block;
    }
    
    .dropdown-menu li {
      list-style: none;
    }
    
    .dropdown-menu a {
      color: #333;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      text-decoration: none;
      transition: all 0.3s;
      font-size: 14px;
    }
    
    .dropdown-menu a:hover {
      background: #f8f9fa;
      color: #2C5530;
    }
    
    .dropdown-menu a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
    
    .dropdown-divider {
      height: 1px;
      background: #eee;
      margin: 5px 0;
    }
    
    .user_link .caret {
      margin-left: 5px;
      border-top: 4px solid;
      border-right: 4px solid transparent;
      border-left: 4px solid transparent;
    }
    
    /* Image styling */
    .img-box img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px 8px 0 0;
    }
  </style>
</head>

<body>

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
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <?php if(isLoggedIn()): ?>
                      <div class="welcome-message">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                        Welcome back, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>! 
                        Ready to explore our latest fabric collection?
                      </div>
                    <?php endif; ?>
                    
                    <h1>
                      Premium Wholesale Fabrics
                    </h1>
                    <p>
                      Your trusted partner for high-quality cotton, silk, linen, and net fabrics. We supply bulk materials to dressmakers, creators, and garment factories across Sri Lanka.
                    </p>
                    <div class="btn-box">
                      <a href="fabrics.php" class="btn1">
                        Browse Fabrics
                      </a>
                      <?php if(!isLoggedIn()): ?>
                        <a href="register.php" class="btn1" style="background: #74bf59d5; margin-left: 10px;">
                          Create Account
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Quality Materials
                    </h1>
                    <p>
                      From luxurious silk to durable cotton, we offer a wide range of fabrics perfect for any creation. Over two years of serving the local fashion industry.
                    </p>
                    <div class="btn-box">
                      <a href="fabrics.php" class="btn1">
                        View Collection
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-7 col-lg-6 ">
                  <div class="detail-box">
                    <h1>
                      Bulk Orders Welcome
                    </h1>
                    <p>
                      Specializing in wholesale quantities for businesses and creators. Get in touch for custom orders and competitive pricing on large fabric quantities.
                    </p>
                    <div class="btn-box">
                      <a href="order.php" class="btn1">
                        Request Quote
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <ol class="carousel-indicators">
            <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
            <li data-target="#customCarousel1" data-slide-to="1"></li>
            <li data-target="#customCarousel1" data-slide-to="2"></li>
          </ol>
        </div>
      </div>

    </section>
    <!-- end slider section -->
  </div>

  <!-- offer section -->

  <section class="offer_section layout_padding-bottom">
    <div class="offer_container">
      <div class="container ">
        <div class="row">
          <div class="col-md-6  ">
            <div class="box ">
              <div class="img-box">
                <img src="images/cotton-offer.jpg" alt="Cotton Fabric">
              </div>
              <div class="detail-box">
                <h5>
                  Premium Cotton
                </h5>
                <h6>
                  <span>Best</span> Quality
                </h6>
                <a href="fabrics.php">
                  View Collection
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-6  ">
            <div class="box ">
              <div class="img-box">
                <img src="images/silk-offer.jpg" alt="Silk Fabric">
              </div>
              <div class="detail-box">
                <h5>
                  Luxury Silk
                </h5>
                <h6>
                  <span>Premium</span> Range
                </h6>
                <a href="fabrics.php">
                  Explore Fabrics
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end offer section -->

  <!-- fabric section -->

  <section class="food_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Our Fabric Collection
        </h2>
        <?php if(isLoggedIn()): ?>
          <p class="text-center" style="color: #2C5530; font-style: italic;">
            <i class="fa fa-star" aria-hidden="true"></i> Logged in users get priority quotes and special offers!
          </p>
        <?php endif; ?>
      </div>

      <ul class="filters_menu">
        <li class="active" data-filter="all">All</li>
        <li data-filter="cotton">Cotton</li>
        <li data-filter="silk">Silk</li>
        <li data-filter="linen">Linen</li>
        <li data-filter="net">Net</li>
      </ul>

      <div class="filters-content">
        <div class="row grid">
          <?php
          // Get limited fabrics for homepage (6 latest fabrics)
          $sql = "SELECT * FROM fabrics WHERE status = 'available' ORDER BY id DESC LIMIT 6";
          $result = $conn->query($sql);
          
          if ($result->num_rows > 0) {
              while ($fabric = $result->fetch_assoc()) {
                  // Determine CSS class based on fabric type
                  $type_class = strtolower($fabric['type']);
                  
                  // Handle image path - FIXED VERSION
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
                  
                  // Case 2: If no image_url, try to guess based on type
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
                  
                  // Generate fabric item
                  ?>
                  <div class="col-sm-6 col-lg-4 all <?php echo $type_class; ?>">
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
              // No fabrics in database - show demo fabrics
              ?>
              <div class="col-sm-6 col-lg-4 all cotton">
                <div class="box">
                  <div>
                    <div class="img-box">
                      <img src="images/cotton1.jpg" alt="Pure Cotton Fabric">
                    </div>
                    <div class="detail-box">
                      <h5>Pure Cotton</h5>
                      <p>Soft, breathable, and perfect for everyday wear. Available in multiple colors and patterns.</p>
                      <div class="options">
                        <h6>LKR 850/m</h6>
                        <a href="order.php">Request Quote</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-4 all silk">
                <div class="box">
                  <div>
                    <div class="img-box">
                      <img src="images/silk1.jpg" alt="Pure Silk Fabric">
                    </div>
                    <div class="detail-box">
                      <h5>Pure Silk</h5>
                      <p>Luxurious and smooth fabric perfect for special occasion outfits and traditional wear.</p>
                      <div class="options">
                        <h6>LKR 2,500/m</h6>
                        <a href="order.php">Request Quote</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-4 all linen">
                <div class="box">
                  <div>
                    <div class="img-box">
                      <img src="images/linen1.jpg" alt="Linen Fabric">
                    </div>
                    <div class="detail-box">
                      <h5>Premium Linen</h5>
                      <p>Natural, breathable fabric ideal for warm climates. Perfect for summer clothing.</p>
                      <div class="options">
                        <h6>LKR 1,200/m</h6>
                        <a href="order.php">Request Quote</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
          }
          ?>
        </div>
      </div>
      <div class="btn-box">
        <a href="fabrics.php">
          View All Fabrics
        </a>
        <?php if(!isLoggedIn()): ?>
          <a href="register.php" style="background: #2C5530; margin-left: 15px;">
            <i class="fa fa-user-plus" aria-hidden="true"></i> Create Account
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- end fabric section -->

  <!-- about section -->

  <section class="about_section layout_padding">
    <div class="container  ">

      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <img src="images/about-img.jpg" alt="About Fashion Fabric">
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
              Fashion Fabric is a wholesale fabric business situated in Udathalawinna, Kandy. For over two years, we have been supplying high-quality raw fabric materials to local customers, dressmakers, small garment factories, and creators.
            </p>
            <p>
              Our shop features a large selection of fabrics including cotton, silk, linen, net, and printed materials, all available in wholesale quantities.
            </p>
            <a href="about.php">
              Read More
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

  <!-- order section -->
  <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Request Fabric Quote
        </h2>
        <?php if(isLoggedIn()): ?>
          <p class="text-center" style="color: #2C5530;">
            <i class="fa fa-check-circle" aria-hidden="true"></i> 
            Your account details will be pre-filled for faster checkout!
          </p>
        <?php endif; ?>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form_container">
            <form id="quoteForm" method="POST" action="order.php">
              <div class="form-group">
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>"
                       placeholder="Your Name" required />
              </div>
              <div class="form-group">
                <input type="text" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($_SESSION['phone']); ?>"
                       placeholder="Phone Number" required />
              </div>
              <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_SESSION['email']); ?>"
                       placeholder="Your Email" required />
              </div>
              <div class="form-group">
                <select class="form-control nice-select wide" id="fabricType" name="fabricType" required>
                  <option value="" disabled selected>Fabric Type</option>
                  <?php
                  // Get unique fabric types from database
                  $type_result = $conn->query("SELECT DISTINCT type FROM fabrics WHERE status = 'available' ORDER BY type");
                  if ($type_result->num_rows > 0) {
                      while ($type_row = $type_result->fetch_assoc()) {
                          $type_name = ucfirst($type_row['type']);
                          echo "<option value='{$type_row['type']}'>{$type_name}</option>";
                      }
                  } else {
                      echo "<option value='cotton'>Cotton</option>";
                      echo "<option value='silk'>Silk</option>";
                      echo "<option value='linen'>Linen</option>";
                      echo "<option value='net'>Net</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity (meters)" min="1" required />
              </div>
              <div class="btn_box">
                <button type="submit">
                  Submit Request
                </button>
                <?php if(!isLoggedIn()): ?>
                  <small class="text-center d-block mt-2">
                    <a href="login.php">Login</a> or <a href="register.php">Register</a> to save your details
                  </small>
                <?php endif; ?>
              </div>
              <div id="formMessage" class="mt-3 text-center" style="display: none;"></div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <div class="map_container ">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d247.31848763819107!2d80.6505137450258!3d7.3431381415162535!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2slk!4v1763042123593!5m2!1sen!2slk" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end order section -->

  <!-- client section -->

  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center psudo_white_primary mb_45">
        <h2>
          What Our Customers Say
        </h2>
      </div>
      <div class="carousel-wrap row ">
        <div class="owl-carousel client_owl-carousel">
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  "Fashion Fabric has been our reliable supplier for over a year. Their cotton quality is exceptional and their wholesale prices are very competitive."
                </p>
                <h6>
                  Local Dressmaker
                </h6>
                <p>
                  Kandy
                </p>
              </div>
              <div class="img-box">
                <img src="images/client1.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
          <div class="item">
            <div class="box">
              <div class="detail-box">
                <p>
                  "The silk collection at Fashion Fabric is amazing! Perfect for our boutique's special occasion outfits. Highly recommended for bulk orders."
                </p>
                <h6>
                  Small Garment Factory
                </h6>
                <p>
                  Central Province
                </p>
              </div>
              <div class="img-box">
                <img src="images/client2.jpg" alt="" class="box-img">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end client section -->

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

  <!-- Form Handling JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const quoteForm = document.getElementById('quoteForm');
      const formMessage = document.getElementById('formMessage');

      // Initialize nice-select if available
      if (typeof $.fn.niceSelect !== 'undefined') {
        $('.nice-select').niceSelect();
      }

      // Filter functionality for index page
      $('.filters_menu li').on('click', function() {
        var filterValue = $(this).data('filter');
        
        // Update active class
        $('.filters_menu li').removeClass('active');
        $(this).addClass('active');
        
        // Show/hide items
        if (filterValue === 'all') {
          $('.all').show();
        } else {
          // Hide all first
          $('.all').hide();
          // Show matching items
          $('.all').each(function() {
            if ($(this).hasClass(filterValue)) {
              $(this).show();
            }
          });
        }
      });

      // Optional: Add JavaScript validation here
      quoteForm.addEventListener('submit', function(e) {
        const quantity = document.getElementById('quantity').value;
        if (quantity < 1) {
          e.preventDefault();
          showMessage('Quantity must be at least 1 meter.', 'error');
          return;
        }
      });

      function showMessage(message, type) {
        formMessage.textContent = message;
        formMessage.style.display = 'block';
        
        if (type === 'success') {
          formMessage.style.color = '#2C5530';
          formMessage.style.backgroundColor = '#d4edda';
          formMessage.style.padding = '10px';
          formMessage.style.borderRadius = '5px';
          formMessage.style.border = '1px solid #c3e6cb';
        } else {
          formMessage.style.color = '#721c24';
          formMessage.style.backgroundColor = '#f8d7da';
          formMessage.style.padding = '10px';
          formMessage.style.borderRadius = '5px';
          formMessage.style.border = '1px solid #f5c6cb';
        }

        // Hide message after 5 seconds
        setTimeout(() => {
          formMessage.style.display = 'none';
        }, 5000);
      }
    });
  </script>
</body>

</html>
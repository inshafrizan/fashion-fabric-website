<?php
require_once 'check_session.php';
require_once 'config/database.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $business = $conn->real_escape_string($_POST['business']);
    $fabric_type = $conn->real_escape_string($_POST['fabricType']);
    $quantity = intval($_POST['quantity']);
    $color = $conn->real_escape_string($_POST['color']);
    $notes = $conn->real_escape_string($_POST['notes']);
    $user_id = $_SESSION['user_id'] ?? 0;
    
    // Insert order into database
    $sql = "INSERT INTO orders (user_id, name, email, phone, business_name, fabric_type, quantity, color_preference, notes, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssiss", $user_id, $name, $email, $phone, $business, $fabric_type, $quantity, $color, $notes);
    
    if ($stmt->execute()) {
        $success = "Order submitted successfully! We'll contact you soon.";
        
        // Clear form if not logged in
        if (!isLoggedIn()) {
            $_POST = array();
        }
    } else {
        $error = "Error submitting order: " . $conn->error;
    }
    $stmt->close();
}
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
  <meta name="keywords" content="fabric, wholesale fabric, order fabric, cotton, silk, linen, Kandy, Sri Lanka" />
  <meta name="description" content="Order premium wholesale fabrics from Fashion Fabric in Kandy, Sri Lanka" />
  <meta name="author" content="Fashion Fabric" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title>Request Quote - Fashion Fabric</title>

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
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }
    
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
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
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.php">
            <span>
              Fashion Fabric
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  mx-auto ">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="fabrics.php">Fabrics</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="order.php">Order <span class="sr-only">(current)</span> </a>
              </li>
            </ul>
            <div class="user_option">
              <?php if(isLoggedIn()): ?>
                <?php if(isAdmin()): ?>
                  <a href="admin/admin_dashboard.php" class="user_link">
                    <i class="fa fa-user"></i>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                  </a>
                <?php else: ?>
                  <a href="profile.php" class="user_link">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                  </a>
                <?php endif; ?>
              <?php else: ?>
                <a href="login.php" class="user_link">
                  <i class="fa fa-user" aria-hidden="true"></i>
                  <span>Login</span>
                </a>
              <?php endif; ?>
              
              <?php if(isLoggedIn()): ?>
                <a href="logout.php" class="order_online">
                  <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                </a>
              <?php else: ?>
                <a href="order.php" class="order_online">
                  Request Quote
                </a>
              <?php endif; ?>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- order section -->
  <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Request Fabric Quote
        </h2>
        <p class="text-center mb-4">
          Submit your fabric requirements and we'll contact you with a quote within 24 hours. 
          <strong>No online payments - this is a quote request only.</strong>
        </p>
      </div>
      
      <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
      <?php endif; ?>
      
      <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <div class="row">
        <div class="col-md-6">
          <div class="form_container">
            <form method="POST" action="">
              <div class="form-group">
                <label for="name" class="form-label">Your Name *</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo isLoggedIn() ? htmlspecialchars($_SESSION['full_name']) : (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''); ?>"
                       placeholder="Enter your full name" required />
              </div>
              <div class="form-group">
                <label for="phone" class="form-label">Phone Number *</label>
                <input type="text" class="form-control" id="phone" name="phone" 
                       value="<?php echo isLoggedIn() ? htmlspecialchars($_SESSION['phone']) : (isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''); ?>"
                       placeholder="Enter your phone number" required />
              </div>
              <div class="form-group">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo isLoggedIn() ? htmlspecialchars($_SESSION['email']) : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''); ?>"
                       placeholder="Enter your email address" required />
              </div>
              <div class="form-group">
                <label for="business" class="form-label">Business/Organization</label>
                <input type="text" class="form-control" id="business" name="business" 
                       value="<?php echo isset($_POST['business']) ? htmlspecialchars($_POST['business']) : ''; ?>"
                       placeholder="Your business name (if applicable)" />
              </div>
              <div class="form-group">
                <label for="fabricType" class="form-label">Fabric Type *</label>
                <select class="form-control nice-select wide" id="fabricType" name="fabricType" required>
                  <option value="" disabled <?php echo !isset($_POST['fabricType']) ? 'selected' : ''; ?>>Select fabric type</option>
                  <option value="cotton" <?php echo (isset($_POST['fabricType']) && $_POST['fabricType'] == 'cotton') ? 'selected' : ''; ?>>Cotton</option>
                  <option value="silk" <?php echo (isset($_POST['fabricType']) && $_POST['fabricType'] == 'silk') ? 'selected' : ''; ?>>Silk</option>
                  <option value="linen" <?php echo (isset($_POST['fabricType']) && $_POST['fabricType'] == 'linen') ? 'selected' : ''; ?>>Linen</option>
                  <option value="net" <?php echo (isset($_POST['fabricType']) && $_POST['fabricType'] == 'net') ? 'selected' : ''; ?>>Net</option>
                  <option value="printed" <?php echo (isset($_POST['fabricType']) && $_POST['fabricType'] == 'printed') ? 'selected' : ''; ?>>Printed Fabric</option>
                  <option value="mixed" <?php echo (isset($_POST['fabricType']) && $_POST['fabricType'] == 'mixed') ? 'selected' : ''; ?>>Mixed Order</option>
                  <option value="other" <?php echo (isset($_POST['fabricType']) && $_POST['fabricType'] == 'other') ? 'selected' : ''; ?>>Other (specify in notes)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="quantity" class="form-label">Quantity (meters) *</label>
                <input type="number" class="form-control" id="quantity" name="quantity" 
                       value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>"
                       placeholder="Enter quantity in meters" min="1" required />
              </div>
              <div class="form-group">
                <label for="color" class="form-label">Color/Pattern Preference</label>
                <input type="text" class="form-control" id="color" name="color" 
                       value="<?php echo isset($_POST['color']) ? htmlspecialchars($_POST['color']) : ''; ?>"
                       placeholder="Preferred colors or patterns" />
              </div>
              <div class="form-group">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special requirements, delivery preferences, or questions"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
              </div>
              <div class="btn_box">
                <button type="submit">
                  Submit Quote Request
                </button>
              </div>
              <div class="mt-3 text-center">
                <small class="text-muted">
                  * Required fields. After submission, Mr. Riyad will contact you to discuss pricing and complete your order.
                </small>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <div class="order_info_container">
            <div class="order_info_box">
              <h4>How Our Order Process Works</h4>
              <div class="process_step">
                <div class="step_number">1</div>
                <div class="step_content">
                  <h5>Submit Your Request</h5>
                  <p>Fill out the form with your fabric requirements and contact details.</p>
                </div>
              </div>
              <div class="process_step">
                <div class="step_number">2</div>
                <div class="step_content">
                  <h5>We Contact You</h5>
                  <p>Mr. Riyad will call or email you within 24 hours to discuss your needs.</p>
                </div>
              </div>
              <div class="process_step">
                <div class="step_number">3</div>
                <div class="step_content">
                  <h5>Receive Quote</h5>
                  <p>We provide a detailed price quote based on your fabric selection and quantity.</p>
                </div>
              </div>
              <div class="process_step">
                <div class="step_number">4</div>
                <div class="step_content">
                  <h5>Confirm & Complete</h5>
                  <p>Once you approve the quote, we process your order and arrange delivery.</p>
                </div>
              </div>
              
              <div class="contact_info mt-4">
                <h5>Prefer to Contact Us Directly?</h5>
                <p><strong>Phone:</strong> +94 XX XXX XXXX</p>
                <p><strong>Email:</strong> info@fashionfabric.lk</p>
                <p><strong>Address:</strong> Udathalawinna, Kandy, Sri Lanka</p>
                <p><strong>Business Hours:</strong> Monday - Saturday, 8:00 AM - 6:00 PM</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end order section -->

  <!-- fabric types section -->
  <section class="fabric_types_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Popular Fabric Types
        </h2>
        <p>
          We specialize in these premium fabric materials
        </p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="fabric_type_box text-center">
            <div class="fabric_icon">
              <i class="fa fa-sun-o" aria-hidden="true"></i>
            </div>
            <h5>Cotton</h5>
            <p>Soft, breathable natural fabric perfect for everyday wear and comfortable clothing.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="fabric_type_box text-center">
            <div class="fabric_icon">
              <i class="fa fa-diamond" aria-hidden="true"></i>
            </div>
            <h5>Silk</h5>
            <p>Luxurious, smooth fabric ideal for special occasions and high-end fashion.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="fabric_type_box text-center">
            <div class="fabric_icon">
              <i class="fa fa-leaf" aria-hidden="true"></i>
            </div>
            <h5>Linen</h5>
            <p>Natural, breathable material perfect for warm climates and summer collections.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end fabric types section -->

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>
              Contact Us
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Udathalawinna, Kandy, Sri Lanka
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call +94 XX XXX XXXX
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  info@fashionfabric.lk
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="" class="footer-logo">
              Fashion Fabric
            </a>
            <p>
              Your trusted wholesale fabric partner in Kandy. Supplying quality materials to creators and businesses for over two years.
            </p>
            <div class="footer_social">
              <a href="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-twitter" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-pinterest" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>
            Business Hours
          </h4>
          <p>
            Monday - Saturday
          </p>
          <p>
            8.00 AM - 6.00 PM
          </p>
        </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
          <a href="index.php">Fashion Fabric</a><br><br>
          &copy; <span id="displayYear"></span> Website Designed Using
          <a href="https://themewagon.com/" target="_blank">Feane Template</a>
        </p>
        <?php if(isLoggedIn()): ?>
          <p style="font-size: 12px; color: #666;">
            <i class="fa fa-user" aria-hidden="true"></i> 
            Logged in as: <?php echo htmlspecialchars($_SESSION['email']); ?>
            | <a href="logout.php" style="color: #2C5530;">Logout</a>
          </p>
        <?php else: ?>
          <p style="font-size: 12px; color: #666;">
            <a href="login.php" style="color: #2C5530;">Login</a> | 
            <a href="register.php" style="color: #2C5530;">Create Account</a>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </footer>
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
</body>
</html>
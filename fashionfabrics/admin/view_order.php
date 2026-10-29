<?php
require_once '../check_session.php';
require_once '../config/database.php';
requireAdmin();

$order = null;
$message = '';

if (!isset($_GET['id'])) {
    $message = "No order ID provided.";
} else {
    $order_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $order = $result->fetch_assoc();
    } else {
        $message = "Order not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Order Details - Fashion Fabric Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="admin_dashboard.php">Fashion Fabric Admin</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="admin_dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <!-- New Button: View Website (Front-end Home) -->
                        <a class="nav-link" href="../index.php" target="_blank">
                            <div class="sb-nav-link-icon"><i class="fas fa-globe"></i></div>
                            View Website
                        </a>

                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link" href="manage_fabrics.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-shirt"></i></div>
                            Manage Fabrics
                        </a>
                        <a class="nav-link active" href="manage_orders.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                            Manage Orders
                        </a>
                        <a class="nav-link" href="manage_users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Manage Users
                        </a>

                        <!-- If you have profile.php -->
                        <a class="nav-link" href="profile.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-circle"></i></div>
                            My Profile
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Order Details</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage_orders.php">Orders</a></li>
                        <li class="breadcrumb-item active">View Order #<?php echo $order ? $order['id'] : ''; ?></li>
                    </ol>

                    <?php if ($message): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
                    <?php elseif ($order): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-shopping-cart me-1"></i>
                                Order ID: #<?php echo $order['id']; ?> 
                                <span class="badge bg-<?php 
                                    echo match($order['status']) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                ?> float-end"><?php echo ucfirst($order['status']); ?></span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Customer Information</h5>
                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                        <?php if (!empty($order['business_name'])): ?>
                                            <p><strong>Business:</strong> <?php echo htmlspecialchars($order['business_name']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Order Details</h5>
                                        <p><strong>Fabric Type:</strong> <?php echo htmlspecialchars($order['fabric_type']); ?></p>
                                        <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?> meters</p>
                                        <?php if (!empty($order['color_preference'])): ?>
                                            <p><strong>Color Preference:</strong> <?php echo htmlspecialchars($order['color_preference']); ?></p>
                                        <?php endif; ?>
                                        <p><strong>Order Date:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                                    </div>
                                </div>

                                <?php if (!empty($order['notes'])): ?>
                                    <hr>
                                    <h5>Additional Notes</h5>
                                    <p><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                                <?php endif; ?>

                                <hr>
                                <a href="manage_orders.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Orders
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Fashion Fabric <?php echo date('Y'); ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
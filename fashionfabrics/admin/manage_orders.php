<?php
session_start();  // Critical: Ensures session messages work

require_once '../check_session.php';
require_once '../config/database.php';
requireAdmin();

// Handle status update

if (isset($_POST['update_status'])) {
    $order_id   = (int) ($_POST['order_id'] ?? 0);
    $new_status = trim($_POST['status'] ?? '');

    $allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    if ($order_id <= 0) {
        $_SESSION['message']      = "Invalid order ID.";
        $_SESSION['message_type'] = 'danger';
    } elseif (!in_array($new_status, $allowed)) {
        $_SESSION['message']      = "Invalid status value: '$new_status'";
        $_SESSION['message_type'] = 'warning';
    } else {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);

        if ($stmt->execute()) {
            $affected = $stmt->affected_rows;
            if ($affected === 1) {
                $_SESSION['message'] = "Order #$order_id updated to <strong>" . ucfirst($new_status) . "</strong> successfully.";
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = "Update ran but affected 0 rows (order #$order_id may not exist or status is already '$new_status').";
                $_SESSION['message_type'] = 'warning';
            }
        } else {
            error_log("Status update failed for order $order_id: " . $stmt->error);
            $_SESSION['message'] = "Database error: " . htmlspecialchars($stmt->error);
            $_SESSION['message_type'] = 'danger';
        }

        $stmt->close();
    }

    // Redirect – comment out temporarily during debugging if you want to see raw output
    header("Location: manage_orders.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Orders - Fashion Fabric Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">

    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="admin_dashboard.php">Fashion Fabric Admin</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <div class="flex-grow-1"></div>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle px-3" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </li>
        </ul>
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
                        <a class="nav-link" href="profile.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-circle"></i></div>
                            My Profile
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?> (Admin)
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Manage Orders</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-shopping-cart me-1"></i>
                            All Customer Orders
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Fabric Type</th>
                                        <th>Quantity (m)</th>
                                        <th>Color</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
                                    while ($row = $result->fetch_assoc()):
                                        $status_class = match($row['status'] ?? 'unknown') {
                                            'pending'    => 'warning',
                                            'processing' => 'info',
                                            'shipped'    => 'primary',
                                            'delivered'  => 'success',
                                            'cancelled'  => 'danger',
                                            default      => 'secondary'
                                        };
                                    ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?><br><small><?= htmlspecialchars($row['business_name'] ?: '—') ?></small></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['phone'] ?: '—') ?></td>
                                            <td><?= htmlspecialchars($row['fabric_type']) ?></td>
                                            <td><?= $row['quantity'] ?></td>
                                            <td><?= htmlspecialchars($row['color_preference'] ?: '—') ?></td>
                                            <td>
                                                <form method="POST" style="display:inline-flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                                    <select name="status" class="form-select form-select-sm" style="min-width:140px;">
                                                        <option value="pending"    <?= $row['status'] === 'pending'    ? 'selected' : '' ?>>Pending</option>
                                                        <option value="processing" <?= $row['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                                        <option value="shipped"    <?= $row['status'] === 'shipped'    ? 'selected' : '' ?>>Shipped</option>
                                                        <option value="delivered"  <?= $row['status'] === 'delivered'  ? 'selected' : '' ?>>Delivered</option>
                                                        <option value="cancelled"  <?= $row['status'] === 'cancelled'  ? 'selected' : '' ?>>Cancelled</option>
                                                    </select>
                                                    <button type="submit" name="update_status" value="1" class="btn btn-sm btn-primary">Update</button>
                                                </form>
                                                <span class="badge bg-<?= $status_class ?> ms-2"><?= ucfirst($row['status'] ?? 'Unknown') ?></span>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                            <td>
                                                <a href="view_order.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info me-1">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="manage_orders.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order permanently?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright © Fashion Fabric <?= date('Y') ?></div>
                        <div>
                            <a href="#">Privacy Policy</a> · <a href="#">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
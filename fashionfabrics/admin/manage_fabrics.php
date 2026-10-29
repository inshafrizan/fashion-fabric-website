<?php
require_once '../check_session.php';
require_once '../config/database.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM fabrics WHERE id = $id");
    header("Location: manage_fabrics.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Fabrics - Fashion Fabric Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <!-- Top Navigation -->
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Brand -->
    <a class="navbar-brand ps-3" href="admin_dashboard.php">Fashion Fabric Admin</a>
    
    <!-- Sidebar Toggle (Hamburger Menu) -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Spacer to push user icon to the far right -->
    <div class="flex-grow-1"></div>

    <!-- User Dropdown - Now aligned to the extreme right -->
    <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle px-3" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="profile.php">
                    <i class="fas fa-user-circle me-2"></i>Profile
                </a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="../logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a></li>
            </ul>
        </li>
    </ul>
</nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
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
                        <a class="nav-link active" href="manage_fabrics.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-shirt"></i></div>
                            Manage Fabrics
                        </a>
                        <a class="nav-link" href="manage_orders.php">
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

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Manage Fabrics</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fabrics</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-shirt me-1"></i>
                            All Fabrics
                            <a href="add_edit_fabric.php" class="btn btn-primary float-end">Add New Fabric</a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Price/m (LKR)</th>
                                        <th>Available (m)</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT * FROM fabrics ORDER BY id DESC");
                                    while ($row = $result->fetch_assoc()) {
                                        $status_badge = $row['status'] == 'available' ? 'success' : ($row['status'] == 'out_of_stock' ? 'warning' : 'secondary');
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                        echo "<td>" . ucfirst($row['type']) . "</td>";
                                        echo "<td>" . number_format($row['price_per_meter'], 2) . "</td>";
                                        echo "<td>" . $row['available_meters'] . "</td>";
                                        echo "<td><span class='badge bg-$status_badge'>" . ucfirst(str_replace('_', ' ', $row['status'])) . "</span></td>";
                                        echo "<td>
                                            <a href='add_edit_fabric.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                            <a href='manage_fabrics.php?delete=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this fabric?\")'>Delete</a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Fashion Fabric <?php echo date('Y'); ?></div>
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
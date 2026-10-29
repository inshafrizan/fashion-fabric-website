<?php
require_once '../check_session.php';
require_once '../config/database.php';
requireAdmin();

$message = '';
$message_type = ''; // success or danger

// Default values
$fabric = [
    'id' => '',
    'name' => '',
    'type' => 'cotton',
    'price_per_meter' => '',
    'available_meters' => '',
    'status' => 'available',
    'image_url' => ''
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $price = floatval($_POST['price']);
    $available = floatval($_POST['available']);
    $status = $_POST['status'];

    // Basic validation
    if (empty($name) || $price < 0 || $available < 0) {
        $message = "Please fill all required fields correctly.";
        $message_type = 'danger';
    } else {
        // Handle image upload
        $image_url = ''; // Will be set to old or new image
        $old_image = ''; // To delete old image if new one uploaded

        // If editing, get current image to possibly delete later
        if ($id > 0) {
            $old_stmt = $conn->prepare("SELECT image_url FROM fabrics WHERE id = ?");
            $old_stmt->bind_param("i", $id);
            $old_stmt->execute();
            $old_result = $old_stmt->get_result();
            if ($old_row = $old_result->fetch_assoc()) {
                $old_image = $old_row['image_url'];
            }
            $old_stmt->close();
        }

        // Process new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/fabrics/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $image_name = time() . '_' . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate image
            $valid_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $valid_types)) {
                $message = "Only JPG, JPEG, PNG & GIF files are allowed.";
                $message_type = 'danger';
            } elseif ($_FILES["image"]["size"] > 5000000) {
                $message = "Image is too large (max 5MB).";
                $message_type = 'danger';
            } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = "uploads/fabrics/" . $image_name;

                // Delete old image if exists
                if (!empty($old_image) && file_exists('../' . $old_image)) {
                    unlink('../' . $old_image);
                }
            } else {
                $message = "Error uploading image.";
                $message_type = 'danger';
            }
        } else {
            // No new image uploaded → keep old one (only if editing)
            $image_url = $old_image;
        }

        // Proceed if no error
        if (empty($message)) {
            if ($id > 0) {
                // Update existing fabric
                $sql = "UPDATE fabrics SET name=?, type=?, price_per_meter=?, available_meters=?, status=?, image_url=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssddssi", $name, $type, $price, $available, $status, $image_url, $id);
            } else {
                // Insert new fabric
                $sql = "INSERT INTO fabrics (name, type, price_per_meter, available_meters, status, image_url) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssddss", $name, $type, $price, $available, $status, $image_url);
            }

            if ($stmt->execute()) {
                $message = $id ? "Fabric updated successfully!" : "New fabric added successfully!";
                $message_type = 'success';
                header("Location: manage_fabrics.php");
                exit();
            } else {
                $message = "Database error: " . $conn->error;
                $message_type = 'danger';
            }
            $stmt->close();
        }
    }
}

// Load fabric data when editing
if (isset($_GET['id']) && !isset($_POST['name'])) { // Avoid reload after POST
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM fabrics WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $fabric = $result->fetch_assoc();
    } else {
        $message = "Fabric not found.";
        $message_type = 'danger';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo $fabric['id'] ? 'Edit' : 'Add'; ?> Fabric - Fashion Fabric Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .img-preview {
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
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

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><?php echo $fabric['id'] ? 'Edit Fabric' : 'Add New Fabric'; ?></h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage_fabrics.php">Fabrics</a></li>
                        <li class="breadcrumb-item active"><?php echo $fabric['id'] ? 'Edit' : 'Add'; ?></li>
                    </ol>

                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <?php if ($fabric['id']): ?>
                                    <input type="hidden" name="id" value="<?php echo $fabric['id']; ?>">
                                <?php endif; ?>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Fabric Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($fabric['name']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Type <span class="text-danger">*</span></label>
                                        <select class="form-select" name="type" required>
                                            <option value="cotton" <?php echo $fabric['type'] == 'cotton' ? 'selected' : ''; ?>>Cotton</option>
                                            <option value="silk" <?php echo $fabric['type'] == 'silk' ? 'selected' : ''; ?>>Silk</option>
                                            <option value="linen" <?php echo $fabric['type'] == 'linen' ? 'selected' : ''; ?>>Linen</option>
                                            <option value="net" <?php echo $fabric['type'] == 'net' ? 'selected' : ''; ?>>Net</option>
                                            <option value="printed" <?php echo $fabric['type'] == 'printed' ? 'selected' : ''; ?>>Printed</option>
                                            <option value="mixed" <?php echo $fabric['type'] == 'mixed' ? 'selected' : ''; ?>>Mixed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Price per Meter (LKR) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="price" value="<?php echo htmlspecialchars($fabric['price_per_meter']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Available Meters <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="available" value="<?php echo htmlspecialchars($fabric['available_meters']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" required>
                                            <option value="available" <?php echo $fabric['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                                            <option value="out_of_stock" <?php echo $fabric['status'] == 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                                            <option value="discontinued" <?php echo $fabric['status'] == 'discontinued' ? 'selected' : ''; ?>>Discontinued</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fabric Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                        <?php if (!empty($fabric['image_url']) && file_exists('../' . $fabric['image_url'])): ?>
                                            <img src="../<?php echo htmlspecialchars($fabric['image_url']); ?>" alt="Current Image" class="img-preview mt-2">
                                            <small class="text-muted d-block mt-1">Current image (leave blank to keep)</small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success me-2">
                                        <i class="fas fa-save"></i> <?php echo $fabric['id'] ? 'Update' : 'Add'; ?> Fabric
                                    </button>
                                    <a href="manage_fabrics.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
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
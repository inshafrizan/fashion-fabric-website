<?php
session_start();
require_once 'config/database.php';
require_once 'check_session.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = ''; // success or error

// Get user data
$user = [];
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows == 1) {
    $user = $user_result->fetch_assoc();
}
$user_stmt->close();

// Handle form submission for updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $business_name = $conn->real_escape_string($_POST['business_name']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Basic validation
    if (empty($full_name)) {
        $message = "Full name is required!";
        $message_type = 'error';
    } else {
        // Check if password update is requested
        $update_password = false;
        $hashed_password = $user['password'];
        
        if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
            // Password change requested
            if (empty($current_password)) {
                $message = "Current password is required to change password!";
                $message_type = 'error';
            } elseif (empty($new_password)) {
                $message = "New password is required!";
                $message_type = 'error';
            } elseif ($new_password !== $confirm_password) {
                $message = "New passwords do not match!";
                $message_type = 'error';
            } elseif (strlen($new_password) < 6) {
                $message = "New password must be at least 6 characters long!";
                $message_type = 'error';
            } elseif (!password_verify($current_password, $user['password'])) {
                $message = "Current password is incorrect!";
                $message_type = 'error';
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password = true;
            }
        }
        
        // If no errors, update the profile
        if (empty($message)) {
            if ($update_password) {
                $update_sql = "UPDATE users SET full_name = ?, phone = ?, business_name = ?, password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ssssi", $full_name, $phone, $business_name, $hashed_password, $user_id);
            } else {
                $update_sql = "UPDATE users SET full_name = ?, phone = ?, business_name = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("sssi", $full_name, $phone, $business_name, $user_id);
            }
            
            if ($update_stmt->execute()) {
                // Update session variables
                $_SESSION['full_name'] = $full_name;
                $_SESSION['phone'] = $phone;
                if ($business_name) {
                    $_SESSION['business_name'] = $business_name;
                }
                
                // Refresh user data
                $user['full_name'] = $full_name;
                $user['phone'] = $phone;
                $user['business_name'] = $business_name;
                if ($update_password) {
                    $user['password'] = $hashed_password;
                }
                
                $message = "Profile updated successfully!";
                $message_type = 'success';
                
                // Clear password fields
                $current_password = $new_password = $confirm_password = '';
            } else {
                $message = "Error updating profile: " . $conn->error;
                $message_type = 'error';
            }
            
            if (isset($update_stmt)) {
                $update_stmt->close();
            }
        }
    }
}

// Get user's orders
$orders = [];
$order_sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$orders_result = $order_stmt->get_result();

if ($orders_result->num_rows > 0) {
    while ($row = $orders_result->fetch_assoc()) {
        $orders[] = $row;
    }
}
$order_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Fashion Fabric</title>
    
    <!-- Include your existing CSS files -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    
    <style>
        .profile-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .profile-header h2 {
            color: #2C5530;
            margin-bottom: 10px;
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
        }
        
        .user-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2C5530 0%, #4a7c59 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .user-icon i {
            font-size: 36px;
            color: white;
        }
        
        .profile-section {
            margin-bottom: 40px;
        }
        
        .section-title {
            color: #2C5530;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 1.5rem;
            font-family: 'Dancing Script', cursive;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #2C5530;
        }
        
        .edit-toggle-btn {
            background: #2C5530;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .edit-toggle-btn:hover {
            background: #4a7c59;
            transform: translateY(-2px);
        }
        
        .edit-toggle-btn i {
            margin-right: 5px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #2C5530;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
            width: 100%;
        }
        
        .form-control:focus {
            border-color: #2C5530;
            box-shadow: 0 0 0 0.2rem rgba(44, 85, 48, 0.25);
        }
        
        label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        
        .password-note {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }
        
        .btn-update {
            background: linear-gradient(135deg, #2C5530 0%, #4a7c59 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 85, 48, 0.3);
        }
        
        .btn-update i {
            margin-right: 8px;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
        }
        
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .message i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .order-table th {
            background: #2C5530;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .order-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        
        .order-table tr:hover {
            background: #f9f9f9;
        }
        
        .order-table tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        
        .profile-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn-action {
            display: inline-flex;
            align-items: center;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        
        .btn-action i {
            margin-right: 8px;
        }
        
        .btn-back {
            background: #f8f9fa;
            color: #2C5530;
            border: 1px solid #2C5530;
        }
        
        .btn-back:hover {
            background: #2C5530;
            color: white;
            text-decoration: none;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #dc3545 0%, #e35d6a 100%);
            color: white;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .no-orders {
            text-align: center;
            padding: 40px;
            color: #666;
            background: #f9f9f9;
            border-radius: 8px;
            border: 2px dashed #ddd;
            margin-top: 20px;
        }
        
        .no-orders i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 15px;
            display: block;
        }
        
        .no-orders h4 {
            color: #666;
            margin-bottom: 10px;
        }
        
        .account-type-badge {
            background: #2C5530;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
            text-transform: uppercase;
        }
        
        .admin-badge {
            background: #ffc107;
            color: #212529;
        }
        
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
        
        /* Edit form specific styles */
        .edit-form {
            display: none; /* Hidden by default */
            background: #f9f9f9;
            padding: 25px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
    
    <script>
        function toggleEditForm() {
            const form = document.getElementById('editForm');
            const viewMode = document.getElementById('viewMode');
            const btn = document.getElementById('editToggleBtn');
            
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                viewMode.style.display = 'none';
                btn.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i> Cancel Edit';
                btn.style.background = '#dc3545';
            } else {
                form.style.display = 'none';
                viewMode.style.display = 'block';
                btn.innerHTML = '<i class="fa fa-edit" aria-hidden="true"></i> Edit Profile';
                btn.style.background = '#2C5530';
            }
        }
        
        function togglePasswordFields() {
            const passwordFields = document.getElementById('passwordFields');
            const btn = document.getElementById('togglePasswordBtn');
            
            if (passwordFields.style.display === 'none' || passwordFields.style.display === '') {
                passwordFields.style.display = 'block';
                btn.innerHTML = 'Cancel Password Change';
            } else {
                passwordFields.style.display = 'none';
                btn.innerHTML = 'Change Password';
                // Clear password fields
                document.getElementById('current_password').value = '';
                document.getElementById('new_password').value = '';
                document.getElementById('confirm_password').value = '';
            }
        }
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="user-icon">
                    <?php if(isAdmin()): ?>
                        <i class="fa fa-user-shield" aria-hidden="true"></i>
                    <?php else: ?>
                        <i class="fa fa-user" aria-hidden="true"></i>
                    <?php endif; ?>
                </div>
                <h2>My Profile</h2>
                <div class="welcome-message">
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    Welcome back, <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                    <?php if(isAdmin()): ?>
                        <span class="account-type-badge admin-badge">Admin</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <i class="fa <?php echo $message_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>" aria-hidden="true"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Personal Information Section -->
            <div class="profile-section">
                <h3 class="section-title">
                    <div style="display: flex; align-items: center;">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                        Personal Information
                    </div>
                    <button id="editToggleBtn" class="edit-toggle-btn" onclick="toggleEditForm()">
                        <i class="fa fa-edit" aria-hidden="true"></i> Edit Profile
                    </button>
                </h3>
                
                <!-- View Mode (Read-only) -->
                <div id="viewMode">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Username</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['phone'] ?: 'Not provided'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Account Type</div>
                            <div class="info-value">
                                <?php 
                                $user_type = $_SESSION['user_type'];
                                echo ucfirst($user_type);
                                ?>
                            </div>
                        </div>
                        <?php if(!empty($user['business_name'])): ?>
                        <div class="info-item">
                            <div class="info-label">Business Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['business_name']); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Edit Mode (Form) -->
                <div id="editForm" class="edit-form">
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name">Full Name *</label>
                                <input type="text" id="full_name" name="full_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" id="business_name" name="business_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['business_name']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small class="password-note">Email cannot be changed</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                            <small class="password-note">Username cannot be changed</small>
                        </div>
                        
                        <!-- Password Change Section -->
                        <div class="form-group">
                            <button type="button" id="togglePasswordBtn" class="btn-action" onclick="togglePasswordFields()" style="background: #6c757d;">
                                Change Password
                            </button>
                        </div>
                        
                        <div id="passwordFields" style="display: none; background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 15px;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="current_password">Current Password *</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control">
                                    <small class="password-note">Required to change password</small>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control">
                                    <small class="password-note">Minimum 6 characters</small>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" name="update_profile" class="btn-update">
                                <i class="fa fa-save" aria-hidden="true"></i> Update Profile
                            </button>
                            <button type="button" class="btn-cancel" onclick="toggleEditForm()">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Orders Section -->
            <div class="profile-section">
                <h3 class="section-title">
                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                    My Orders
                </h3>
                
                <?php if(empty($orders)): ?>
                    <div class="no-orders">
                        <i class="fa fa-box-open" aria-hidden="true"></i>
                        <h4>No Orders Yet</h4>
                        <p>You haven't placed any orders yet. Start shopping now!</p>
                        <a href="fabrics.php" class="btn-action" style="background: #2C5530; color: white; margin-top: 15px;">
                            <i class="fa fa-shopping-bag" aria-hidden="true"></i> Browse Fabrics
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Fabric Type</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($order['fabric_type'])); ?></td>
                                        <td><?php echo $order['quantity']; ?> meters</td>
                                        <td>
                                            <?php 
                                            $status = $order['status'] ?? 'pending';
                                            $status_class = 'status-' . $status;
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="profile-actions">
                <a href="index.php" class="btn-action btn-back">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Home
                </a>
                <a href="logout.php" class="btn-action btn-logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                </a>
            </div>
        </div>
    </div>
    
    <!-- Include your footer -->
    <?php include 'footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    
    <script>
        // Auto-refresh orders every 30 seconds if there are orders
        <?php if(!empty($orders)): ?>
        setTimeout(function() {
            location.reload();
        }, 30000);
        <?php endif; ?>
        
        // If there's a success message and we're in edit mode, show the view mode
        <?php if($message_type == 'success'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const editForm = document.getElementById('editForm');
            const viewMode = document.getElementById('viewMode');
            const btn = document.getElementById('editToggleBtn');
            
            if (editForm && viewMode && btn) {
                editForm.style.display = 'none';
                viewMode.style.display = 'block';
                btn.innerHTML = '<i class="fa fa-edit" aria-hidden="true"></i> Edit Profile';
                btn.style.background = '#2C5530';
            }
            
            // Hide password fields if they're visible
            const passwordFields = document.getElementById('passwordFields');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            if (passwordFields && togglePasswordBtn) {
                passwordFields.style.display = 'none';
                togglePasswordBtn.innerHTML = 'Change Password';
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>
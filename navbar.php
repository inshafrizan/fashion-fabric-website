<?php
require_once 'check_session.php';
?>
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
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'fabrics.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="fabrics.php">Fabrics</a>
            </li>
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'order.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="order.php">Order</a>
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
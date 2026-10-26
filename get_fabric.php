<?php
session_start();
require_once 'config/database.php';

// Check if user is admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    exit('Access denied');
}

if (isset($_GET['id'])) {
    $fabric_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM fabrics WHERE id = ?");
    $stmt->bind_param("i", $fabric_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($fabric = $result->fetch_assoc()): ?>
        <input type="hidden" name="id" value="<?php echo $fabric['id']; ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label form-label-required">Fabric Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($fabric['name']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label form-label-required">Type</label>
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
        </div>
        
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($fabric['description']); ?></textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label form-label-required">Price per Meter (LKR)</label>
                    <input type="number" class="form-control" name="price" step="0.01" min="0" 
                           value="<?php echo $fabric['price_per_meter']; ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label form-label-required">Available Meters</label>
                    <input type="number" class="form-control" name="available" step="0.01" min="0" 
                           value="<?php echo $fabric['available_meters']; ?>" required>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label form-label-required">Status</label>
            <select class="form-select" name="status" required>
                <option value="available" <?php echo $fabric['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="out_of_stock" <?php echo $fabric['status'] == 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                <option value="discontinued" <?php echo $fabric['status'] == 'discontinued' ? 'selected' : ''; ?>>Discontinued</option>
            </select>
        </div>
        
        <?php if (!empty($fabric['image_url']) && file_exists($fabric['image_url'])): ?>
            <div class="mb-3">
                <label class="form-label">Current Image</label>
                <div>
                    <img src="<?php echo $fabric['image_url']; ?>" alt="Current Image" class="img-fluid rounded" style="max-height: 150px;">
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-danger">Fabric not found</div>
    <?php endif;
}
?>
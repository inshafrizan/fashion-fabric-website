// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            
            // Save preference in localStorage
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }
    
    // Restore sidebar state from localStorage
    if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        document.body.classList.add('sb-sidenav-toggled');
    }
    
    // Close sidebar when clicking on overlay (mobile)
    const layoutContent = document.getElementById('layoutSidenav_content');
    if (layoutContent) {
        layoutContent.addEventListener('click', function(e) {
            if (document.body.classList.contains('sb-sidenav-toggled') && window.innerWidth < 992) {
                document.body.classList.remove('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', 'false');
            }
        });
    }
    
    // Initialize DataTables for admin tables
    if (typeof simpleDatatables !== 'undefined') {
        // Initialize tables with class 'datatable-admin'
        const adminTables = document.querySelectorAll('.datatable-admin');
        adminTables.forEach(table => {
            new simpleDatatables.DataTable(table, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                labels: {
                    placeholder: "Search...",
                    perPage: "entries per page",
                    noRows: "No data found",
                    info: "Showing {start} to {end} of {rows} entries"
                }
            });
        });
        
        // Initialize tables with ID containing 'Table'
        const tableElements = document.querySelectorAll('[id*="Table"]:not(.initialized)');
        tableElements.forEach(table => {
            if (!table.classList.contains('dataTable')) {
                new simpleDatatables.DataTable(table, {
                    perPage: 10,
                    perPageSelect: [5, 10, 25, 50],
                    labels: {
                        placeholder: "Search...",
                        perPage: "entries per page",
                        noRows: "No data found",
                        info: "Showing {start} to {end} of {rows} entries"
                    }
                });
                table.classList.add('initialized');
            }
        });
    }
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Confirm before delete actions
    const deleteButtons = document.querySelectorAll('[onclick*="confirm"], .btn-delete, a[href*="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    
    // Form validation enhancement
    const adminForms = document.querySelectorAll('form');
    adminForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add error message if not present
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'This field is required';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    
    // Real-time form field validation
    const formFields = document.querySelectorAll('input, textarea, select');
    formFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Toggle password visibility
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            
            if (targetInput) {
                const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                targetInput.setAttribute('type', type);
                
                // Toggle icon
                const icon = this.querySelector('i');
                if (icon) {
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            }
        });
    });
    
    // Bulk actions
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Update selected count
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
        });
    });
    
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
        const countElement = document.getElementById('selectedCount');
        if (countElement) {
            countElement.textContent = selectedCount;
        }
    }
    
    // Chart initialization (if Chart.js is available)
    if (typeof Chart !== 'undefined') {
        const chartElements = document.querySelectorAll('canvas');
        chartElements.forEach(canvas => {
            // You can add specific chart initialization here
            // based on canvas ID or data attributes
        });
    }
    
    // Print functionality
    const printButtons = document.querySelectorAll('.btn-print');
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            window.print();
        });
    });
    
    // Export functionality
    const exportButtons = document.querySelectorAll('.btn-export');
    exportButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const format = this.getAttribute('data-format');
            const url = this.getAttribute('data-url');
            
            if (format && url) {
                e.preventDefault();
                window.location.href = url + '?format=' + format;
            }
        });
    });
    
    // Real-time updates (for order notifications, etc.)
    if (window.location.pathname.includes('admin_dashboard') || 
        window.location.pathname.includes('admin_orders')) {
        setInterval(function() {
            // You can add AJAX calls here for real-time updates
            // Example: check for new orders every 30 seconds
        }, 30000);
    }
});
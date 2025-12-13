// Enhanced Admin Theme JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Enhanced table row hover effects
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Enhanced button click effects
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.querySelector('.btn-close')) {
                alert.querySelector('.btn-close').click();
            }
        }, 5000);
    });
    
    // Enhanced sidebar navigation
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            // Add active class to clicked link
            this.classList.add('active');
        });
    });
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Enhanced form validation feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Show error message
                showNotification('Please fill in all required fields', 'error');
            }
        });
    });
    
    // Loading states for buttons
    function addLoadingState(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="loading-spinner me-2"></span>Loading...';
        button.disabled = true;
        
        return function() {
            button.innerHTML = originalText;
            button.disabled = false;
        };
    }
    
    // Enhanced delete confirmations
    // Only target buttons that are actually for deletion
    const deleteButtons = document.querySelectorAll('[data-action="delete"]');
    // Alternative: target buttons with "delete" in onclick but not "enroll"
    // const deleteButtons = document.querySelectorAll('[onclick*="delete"]:not([onclick*="enroll"])');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create custom confirmation modal
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">
                                <i class="bi bi-trash me-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.querySelector('#confirmDelete').addEventListener('click', () => {
                window.location.href = this.href;
            });
            
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
            });
        });
    });
    
    // Enrollment confirmations
    const enrollButtons = document.querySelectorAll('[data-action="enroll"]');
    enrollButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create custom enrollment confirmation modal
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-mortarboard me-2"></i>Confirm Enrollment
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>🎓 Ready to start learning? Enroll in this course now!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" id="confirmEnroll">
                                <i class="bi bi-check-circle me-2"></i>Enroll Now
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.querySelector('#confirmEnroll').addEventListener('click', () => {
                window.location.href = this.href;
            });
            
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
            });
        });
    });
    
    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    
    // Mobile sidebar toggle
    function createMobileSidebarToggle() {
        if (window.innerWidth <= 768) {
            const navbar = document.querySelector('.navbar');
            if (navbar && !navbar.querySelector('.sidebar-toggle')) {
                const toggleButton = document.createElement('button');
                toggleButton.className = 'btn btn-outline-primary sidebar-toggle me-3';
                toggleButton.innerHTML = '<i class="bi bi-list"></i>';
                
                navbar.querySelector('.container-fluid').prepend(toggleButton);
                
                toggleButton.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    const overlay = document.querySelector('.sidebar-overlay') || createOverlay();
                    
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            }
        }
    }
    
    function createOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
        
        overlay.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.remove('show');
            this.classList.remove('show');
        });
        
        return overlay;
    }
    
    // Initialize mobile features
    createMobileSidebarToggle();
    window.addEventListener('resize', createMobileSidebarToggle);
    
    // Enhanced search functionality
    const searchInputs = document.querySelectorAll('input[type="search"], .search-input');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.card').querySelector('table tbody');
            
            if (table) {
                const rows = table.querySelectorAll('tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            }
        });
    });
    
    // Auto-save form data to localStorage
    const formInputs = document.querySelectorAll('input, textarea, select');
    formInputs.forEach(input => {
        // Load saved data
        const savedValue = localStorage.getItem(`form_${input.name}`);
        if (savedValue && input.type !== 'password') {
            input.value = savedValue;
        }
        
        // Save data on change
        input.addEventListener('change', function() {
            if (this.type !== 'password') {
                localStorage.setItem(`form_${this.name}`, this.value);
            }
        });
    });
    
    // Clear saved form data on successful submission
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const inputs = this.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                localStorage.removeItem(`form_${input.name}`);
            });
        });
    });
    
    // Enhanced data tables with sorting
    const tables = document.querySelectorAll('.table');
    tables.forEach(table => {
        const headers = table.querySelectorAll('thead th');
        headers.forEach((header, index) => {
            if (header.textContent.trim() && !header.querySelector('.no-sort')) {
                header.style.cursor = 'pointer';
                header.innerHTML += ' <i class="bi bi-arrow-down-up ms-1 text-muted"></i>';
                
                header.addEventListener('click', function() {
                    sortTable(table, index);
                });
            }
        });
    });
    
    function sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const isAscending = table.dataset.sortOrder !== 'asc';
        
        rows.sort((a, b) => {
            const aText = a.cells[columnIndex].textContent.trim();
            const bText = b.cells[columnIndex].textContent.trim();
            
            // Check if numeric
            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return isAscending ? aNum - bNum : bNum - aNum;
            }
            
            return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
        });
        
        rows.forEach(row => tbody.appendChild(row));
        table.dataset.sortOrder = isAscending ? 'asc' : 'desc';
        
        // Update sort indicators
        const headers = table.querySelectorAll('thead th i');
        headers.forEach(icon => {
            icon.className = 'bi bi-arrow-down-up ms-1 text-muted';
        });
        
        const currentHeader = table.querySelectorAll('thead th')[columnIndex].querySelector('i');
        currentHeader.className = `bi bi-arrow-${isAscending ? 'up' : 'down'} ms-1 text-primary`;
    }
    
    // Performance monitoring
    console.log('🎨 Enhanced Admin Theme loaded successfully!');
    console.log('⚡ Page load time:', performance.now().toFixed(2) + 'ms');
});

// Add ripple effect CSS
const rippleCSS = `
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
`;

const style = document.createElement('style');
style.textContent = rippleCSS;
document.head.appendChild(style);
<?php $this->load->view('templates/header', ['page_title' => 'Manage Users']); ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Success!</h5>
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Users</span>
                <span class="info-box-number"><?= count($users) ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-shield"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Admins</span>
                <span class="info-box-number"><?= count(array_filter($users, function($u) { return $u->role == 'admin'; })) ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Teachers</span>
                <span class="info-box-number"><?= count(array_filter($users, function($u) { return $u->role == 'teacher'; })) ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-graduate"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Students</span>
                <span class="info-box-number"><?= count(array_filter($users, function($u) { return $u->role == 'student'; })) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="searchInput" class="form-label">
                            <i class="bi bi-search"></i> Search
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="searchInput" 
                               placeholder="Search by name, email, or ID...">
                    </div>
                    <div class="col-md-3">
                        <label for="roleFilter" class="form-label">
                            <i class="bi bi-shield"></i> Role
                        </label>
                        <select class="form-select" id="roleFilter">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sortBy" class="form-label">
                            <i class="bi bi-sort-down"></i> Sort By
                        </label>
                        <select class="form-select" id="sortBy">
                            <option value="name">Name</option>
                            <option value="email">Email</option>
                            <option value="role">Role</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-muted">
                        <i class="bi bi-info-circle"></i> 
                        Showing <strong id="resultCount"><?= count($users) ?></strong> user(s)
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="fw-bold"><i class="bi bi-hash me-2"></i>ID</th>
                                <th class="fw-bold"><i class="bi bi-person me-2"></i>Name</th>
                                <th class="fw-bold"><i class="bi bi-envelope me-2"></i>Email</th>
                                <th class="fw-bold"><i class="bi bi-shield me-2"></i>Role</th>
                                <th class="fw-bold"><i class="bi bi-calendar me-2"></i>Registered</th>
                                <th class="fw-bold"><i class="bi bi-gear me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php if(isset($users) && count($users) > 0): ?>
                                <?php foreach($users as $user_item): ?>
                                    <tr class="user-row" 
                                        data-id="<?= $user_item->id ?>"
                                        data-name="<?= strtolower($user_item->name) ?>"
                                        data-email="<?= strtolower($user_item->email) ?>"
                                        data-role="<?= $user_item->role ?>"
                                        data-date="<?= strtotime($user_item->created_at) ?>">
                                        <td><?= $user_item->id ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                                    <?= strtoupper(substr($user_item->name, 0, 1)) ?>
                                                </div>
                                                <span class="fw-medium"><?= $user_item->name ?></span>
                                            </div>
                                        </td>
                                        <td><?= $user_item->email ?></td>
                                        <td>
                                            <?php if($user_item->role == 'admin'): ?>
                                                <span class="badge bg-danger">Admin</span>
                                            <?php elseif($user_item->role == 'teacher'): ?>
                                                <span class="badge bg-info">Teacher</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Student</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($user_item->created_at)) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('admin/edit_user/'.$user_item->id) ?>" class="btn btn-sm btn-outline-primary" title="Edit User">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <?php if($user_item->id != $this->session->userdata('user_id')): ?>
                                                    <a href="<?= base_url('admin/delete_user/'.$user_item->id) ?>" class="btn btn-sm btn-outline-danger" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noResults">
                                    <td colspan="6" class="text-center">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const sortBy = document.getElementById('sortBy');
    const tableBody = document.getElementById('usersTableBody');
    const resultCount = document.getElementById('resultCount');
    
    // Get all user rows
    let allRows = Array.from(document.querySelectorAll('.user-row'));
    
    // Filter and sort function
    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedRole = roleFilter.value;
        const sortField = sortBy.value;
        
        // Filter rows
        let visibleRows = allRows.filter(row => {
            const name = row.dataset.name;
            const email = row.dataset.email;
            const id = row.dataset.id;
            const role = row.dataset.role;
            
            // Search filter
            const matchesSearch = searchTerm === '' || 
                                name.includes(searchTerm) || 
                                email.includes(searchTerm) || 
                                id.includes(searchTerm);
            
            // Role filter
            const matchesRole = selectedRole === '' || role === selectedRole;
            
            return matchesSearch && matchesRole;
        });
        
        // Sort rows
        visibleRows.sort((a, b) => {
            let aVal, bVal;
            
            switch(sortField) {
                case 'name':
                    aVal = a.dataset.name;
                    bVal = b.dataset.name;
                    return aVal.localeCompare(bVal);
                case 'email':
                    aVal = a.dataset.email;
                    bVal = b.dataset.email;
                    return aVal.localeCompare(bVal);
                case 'role':
                    aVal = a.dataset.role;
                    bVal = b.dataset.role;
                    return aVal.localeCompare(bVal);
                case 'date':
                    aVal = parseInt(a.dataset.date);
                    bVal = parseInt(b.dataset.date);
                    return bVal - aVal; // Newest first
                default:
                    return 0;
            }
        });
        
        // Clear table
        tableBody.innerHTML = '';
        
        // Display filtered and sorted rows
        if (visibleRows.length > 0) {
            visibleRows.forEach(row => {
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i><p class="mt-2 text-muted">No users found matching your criteria</p></td></tr>';
        }
        
        // Update count
        resultCount.textContent = visibleRows.length;
        
        // Add fade-in animation
        tableBody.style.opacity = '0';
        setTimeout(() => {
            tableBody.style.transition = 'opacity 0.3s';
            tableBody.style.opacity = '1';
        }, 10);
    }
    
    // Event listeners
    searchInput.addEventListener('keyup', filterAndSort);
    roleFilter.addEventListener('change', filterAndSort);
    sortBy.addEventListener('change', filterAndSort);
    
    // Initial load
    filterAndSort();
});
</script>

<?php $this->load->view('templates/footer'); ?>

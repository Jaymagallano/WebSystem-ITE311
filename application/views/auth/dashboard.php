<?php $this->load->view('templates/header', ['page_title' => 'Dashboard']); ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <h2>Welcome, <?= $user['name'] ?>!</h2>
        <p class="text-muted">Here's what's happening with your account today.</p>
    </div>
</div>

<?php if($user['role'] == 'admin'): ?>
    <!-- Admin Dashboard -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card users">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h3 class="mb-0"><?= isset($total_users) ? $total_users : 0 ?></h3>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card admin">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Admins</h6>
                            <h3 class="mb-0"><?= isset($total_admins) ? $total_admins : 0 ?></h3>
                        </div>
                        <div class="text-danger" style="font-size: 2rem;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card teacher">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Teachers</h6>
                            <h3 class="mb-0"><?= isset($total_teachers) ? $total_teachers : 0 ?></h3>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card student">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Students</h6>
                            <h3 class="mb-0"><?= isset($total_students) ? $total_students : 0 ?></h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($recent_users) && count($recent_users) > 0): ?>
                                    <?php foreach($recent_users as $user_item): ?>
                                        <tr>
                                            <td><?= $user_item->id ?></td>
                                            <td><?= $user_item->name ?></td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No users found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-plus" style="font-size: 3rem; color: #0d6efd;"></i>
                    <h5 class="mt-3">Add New User</h5>
                    <p class="text-muted">Create a new user account</p>
                    <a href="#" class="btn btn-primary">Add User</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-book" style="font-size: 3rem; color: #198754;"></i>
                    <h5 class="mt-3">Manage Courses</h5>
                    <p class="text-muted">View and edit all courses</p>
                    <a href="#" class="btn btn-success">View Courses</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-bar-chart" style="font-size: 3rem; color: #ffc107;"></i>
                    <h5 class="mt-3">System Reports</h5>
                    <p class="text-muted">Generate system reports</p>
                    <a href="#" class="btn btn-warning">View Reports</a>
                </div>
            </div>
        </div>
    </div>

<?php elseif($user['role'] == 'teacher'): ?>
    <!-- Teacher Dashboard -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card student">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">My Students</h6>
                            <h3 class="mb-0"><?= isset($total_students) ? $total_students : 0 ?></h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card teacher">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">My Courses</h6>
                            <h3 class="mb-0">0</h3>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card users">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Assignments</h6>
                            <h3 class="mb-0">0</h3>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Students -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Recent Students</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Enrolled</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($recent_students) && count($recent_students) > 0): ?>
                                    <?php foreach($recent_students as $student): ?>
                                        <tr>
                                            <td><?= $student->id ?></td>
                                            <td><?= $student->name ?></td>
                                            <td><?= $student->email ?></td>
                                            <td><?= date('M d, Y', strtotime($student->created_at)) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No students found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Create Course
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="bi bi-clipboard-plus"></i> New Assignment
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="bi bi-calculator"></i> Grade Students
                        </a>
                        <a href="#" class="btn btn-outline-warning">
                            <i class="bi bi-file-earmark-text"></i> Upload Materials
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Student Dashboard -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card teacher">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">My Teachers</h6>
                            <h3 class="mb-0"><?= isset($total_teachers) ? $total_teachers : 0 ?></h3>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card student">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Enrolled Courses</h6>
                            <h3 class="mb-0">0</h3>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card users">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending Tasks</h6>
                            <h3 class="mb-0">0</h3>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> My Courses</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">You are not enrolled in any courses yet.</p>
                    <a href="#" class="btn btn-primary">Browse Courses</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Upcoming Assignments</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">No assignments at this time.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-calendar3"></i> Today's Schedule</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">No classes scheduled for today.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> My Performance</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="text-success">0%</h3>
                    <p class="text-muted mb-0">Average Grade</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>

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
        <div class="card" style="background: var(--primary-color); color: white; border: none;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-1 fw-normal">Welcome, <?= $user['name'] ?></h4>
                        <p class="mb-0 small">Dashboard Overview</p>
                    </div>
                    <div>
                        <i class="bi bi-person-circle" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
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
                            <h6 class="text-muted mb-2 fw-semibold">Total Users</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_users) ? $total_users : 0 ?></h3>
                        </div>
                        <div class="text-primary pulse" style="font-size: 2.5rem; color: #3182ce !important;">
                            <i class="bi bi-people-fill"></i>
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
                            <h6 class="text-muted mb-2 fw-semibold">Admins</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_admins) ? $total_admins : 0 ?></h3>
                        </div>
                        <div class="text-danger pulse" style="font-size: 2.5rem; color: #c53030 !important;">
                            <i class="bi bi-shield-fill-check"></i>
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
                            <h6 class="text-muted mb-2 fw-semibold">Teachers</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_teachers) ? $total_teachers : 0 ?></h3>
                        </div>
                        <div class="text-info pulse" style="font-size: 2.5rem; color: #2c7a7b !important;">
                            <i class="bi bi-person-badge-fill"></i>
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
                            <h6 class="text-muted mb-2 fw-semibold">Students</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_students) ? $total_students : 0 ?></h3>
                        </div>
                        <div class="text-success pulse" style="font-size: 2.5rem; color: #2f855a !important;">
                            <i class="bi bi-mortarboard-fill"></i>
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
                                                    <span class="badge" style="background: linear-gradient(135deg, #c53030 0%, #9b2c2c 100%);">Admin</span>
                                                <?php elseif($user_item->role == 'teacher'): ?>
                                                    <span class="badge" style="background: linear-gradient(135deg, #2c7a7b 0%, #285e61 100%);">Teacher</span>
                                                <?php else: ?>
                                                    <span class="badge" style="background: linear-gradient(135deg, #2f855a 0%, #276749 100%);">Student</span>
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
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-person-plus-fill" style="font-size: 3.5rem; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Add New User</h5>
                    <p class="text-muted mb-3">Create a new user account</p>
                    <a href="<?= base_url('admin/create_user') ?>" class="btn btn-primary px-4">
                        <i class="bi bi-plus-circle me-2"></i>Add User
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-book-fill" style="font-size: 3.5rem; background: var(--warning-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Manage Courses</h5>
                    <p class="text-muted mb-3">View and edit all courses</p>
                    <a href="<?= base_url('admin/courses') ?>" class="btn btn-success px-4">
                        <i class="bi bi-gear me-2"></i>View Courses
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-bar-chart-fill" style="font-size: 3.5rem; background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">System Reports</h5>
                    <p class="text-muted mb-3">Generate system reports</p>
                    <a href="<?= base_url('admin/reports') ?>" class="btn btn-danger px-4">
                        <i class="bi bi-graph-up me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php elseif($user['role'] == 'teacher'): ?>
    <!-- Teacher Dashboard -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stat-card student floating">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">My Students</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_students) ? $total_students : 0 ?></h3>
                            <small class="text-success"><i class="bi bi-arrow-up"></i> Active learners</small>
                        </div>
                        <div class="text-success pulse" style="font-size: 2.5rem; color: #2f855a !important;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card teacher floating" style="animation-delay: 0.1s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">My Courses</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_courses) ? $total_courses : 0 ?></h3>
                            <small class="text-info"><i class="bi bi-book"></i> Published courses</small>
                        </div>
                        <div class="text-info pulse" style="font-size: 2.5rem; color: #2c7a7b !important;">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card users floating" style="animation-delay: 0.2s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Assignments</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_assignments) ? $total_assignments : 0 ?></h3>
                            <small class="text-primary"><i class="bi bi-clipboard-check"></i> Active tasks</small>
                        </div>
                        <div class="text-primary pulse" style="font-size: 2.5rem; color: #3182ce !important;">
                            <i class="bi bi-clipboard-check-fill"></i>
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
                <div class="card-header border-0" style="background: var(--primary-gradient); color: white; border-radius: 20px 20px 0 0;">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-lightning-charge-fill me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('teacher/create_course') ?>" class="btn btn-outline-primary btn-lg text-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-plus-circle-fill me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="fw-semibold">Create Course</div>
                                    <small class="text-muted">Start a new course</small>
                                </div>
                            </div>
                        </a>
                        <a href="<?= base_url('teacher/create_assignment') ?>" class="btn btn-outline-success btn-lg text-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clipboard-plus-fill me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="fw-semibold">New Assignment</div>
                                    <small class="text-muted">Create student task</small>
                                </div>
                            </div>
                        </a>
                        <a href="<?= base_url('teacher/grades') ?>" class="btn btn-outline-info btn-lg text-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calculator-fill me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="fw-semibold">Grade Students</div>
                                    <small class="text-muted">Review submissions</small>
                                </div>
                            </div>
                        </a>
                        <a href="<?= base_url('teacher/materials') ?>" class="btn btn-outline-warning btn-lg text-start">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text-fill me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="fw-semibold">Upload Materials</div>
                                    <small class="text-muted">Share resources</small>
                                </div>
                            </div>
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
            <div class="card stat-card teacher floating">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">My Teachers</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_teachers) ? $total_teachers : 0 ?></h3>
                            <small class="text-info"><i class="bi bi-mortarboard"></i> Expert instructors</small>
                        </div>
                        <div class="text-info pulse" style="font-size: 2.5rem; color: #2c7a7b !important;">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card student floating" style="animation-delay: 0.1s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Enrolled Courses</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_enrolled_courses) ? $total_enrolled_courses : 0 ?></h3>
                            <small class="text-success"><i class="bi bi-bookmark-check"></i> Active learning</small>
                        </div>
                        <div class="text-success pulse" style="font-size: 2.5rem; color: #2f855a !important;">
                            <i class="bi bi-book-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card stat-card users floating" style="animation-delay: 0.2s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Pending Tasks</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($total_pending_assignments) ? $total_pending_assignments : 0 ?></h3>
                            <small class="text-primary"><i class="bi bi-clock"></i> To complete</small>
                        </div>
                        <div class="text-primary pulse" style="font-size: 2.5rem; color: #3182ce !important;">
                            <i class="bi bi-clipboard-check-fill"></i>
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

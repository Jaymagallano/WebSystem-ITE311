<?php $this->load->view('templates/header', ['page_title' => 'System Reports']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-bar-chart"></i> System Reports</h2>
    </div>
</div>

<!-- Statistics Cards -->
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

<!-- Course Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Total Courses</h5>
            </div>
            <div class="card-body text-center">
                <h1 class="display-4 text-primary"><?= isset($total_courses) ? $total_courses : 0 ?></h1>
                <p class="text-muted">Active Courses</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Course Enrollments</h5>
            </div>
            <div class="card-body">
                <?php if(isset($course_enrollments) && count($course_enrollments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Enrollments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($course_enrollments as $enrollment): ?>
                                    <tr>
                                        <td><?= $enrollment->title ?></td>
                                        <td><span class="badge bg-success"><?= $enrollment->enrollment_count ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No enrollment data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- User Growth Chart Placeholder -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">User Growth</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-graph-up" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">Chart visualization will be available soon</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Reports -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Export Reports</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <button class="btn btn-outline-success w-100">
                            <i class="bi bi-file-earmark-excel"></i> Export to Excel
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-danger w-100">
                            <i class="bi bi-file-earmark-pdf"></i> Export to PDF
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-primary w-100">
                            <i class="bi bi-printer"></i> Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

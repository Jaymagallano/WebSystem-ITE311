<?php $this->load->view('templates/header', ['page_title' => 'Teaching Reports']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-bar-chart"></i> Teaching Reports</h2>
        <p class="text-muted mb-0 small">Overview of your courses, students, and assessment activity.</p>
    </div>
</div>

<!-- High-level stats for this teacher -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card teacher">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">My Courses</h6>
                        <h3 class="mb-0"><?= isset($courses) ? count($courses) : 0 ?></h3>
                    </div>
                    <div class="stat-icon text-info">
                        <i class="bi bi-journal-bookmark-fill"></i>
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
                        <h6 class="text-muted mb-2">Total Students</h6>
                        <h3 class="mb-0"><?= isset($total_students) ? $total_students : 0 ?></h3>
                    </div>
                    <div class="stat-icon text-success">
                        <i class="bi bi-people-fill"></i>
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
                        <h6 class="text-muted mb-2">Assignments & Submissions</h6>
                        <h3 class="mb-0"><?= isset($total_assignments) ? $total_assignments : 0 ?> / <?= isset($total_submissions) ? $total_submissions : 0 ?></h3>
                        <small class="text-muted">Assignments / Submissions</small>
                    </div>
                    <div class="stat-icon text-primary">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Average grade overview -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-award me-2"></i>Overall Average Grade</h5>
            </div>
            <div class="card-body text-center">
                <h1 class="display-5 text-success mb-1"><?= number_format($average_grade ?? 0, 2) ?></h1>
                <p class="text-muted mb-0 small">Average points across all graded submissions</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Courses Overview</h5>
                <small class="text-muted">Select a course for detailed report</small>
            </div>
            <div class="card-body">
                <?php if (!empty($courses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Code</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td>
                                            <strong><?= html_escape($course->title) ?></strong>
                                        </td>
                                        <td><span class="badge bg-secondary"><?= html_escape($course->code) ?></span></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('teacher/course_report/' . $course->id) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-graph-up"></i> View Report
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">You don't have any courses yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
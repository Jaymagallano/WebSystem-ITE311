<?php $this->load->view('templates/header', ['page_title' => 'My Courses']); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: var(--primary-color); color: white; border: none;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1 fw-normal">
                            <i class="bi bi-book me-2"></i>Course Management
                        </h3>
                        <p class="mb-0 small">Create and manage your courses</p>
                    </div>
                    <div>
                        <a href="<?= base_url('teacher/create_course') ?>" class="btn btn-light">
                            <i class="bi bi-plus me-2"></i>New Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php if (isset($courses) && count($courses) > 0): ?>
        <?php foreach ($courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 course-card">
                    <div class="card-header border-0"
                        style="background: var(--primary-gradient); color: white; border-radius: 0px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1 fw-bold"><?= $course->title ?></h5>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-code-square me-1"></i><?= $course->code ?>
                                </span>
                            </div>
                            <div>
                                <i class="bi bi-book-fill" style="font-size: 2rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="card-text text-muted mb-3"><?= substr($course->description, 0, 100) ?>...</p>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <i class="bi bi-calendar3 me-2"></i>
                            <small>Created: <?= date('M d, Y', strtotime($course->created_at)) ?></small>
                        </div>
                        <div class="course-stats row text-center">
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="bi bi-people-fill text-primary"></i>
                                    <small class="d-block text-muted">Students</small>
                                    <strong><?= $course->student_count ?? 0 ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="bi bi-clipboard-check-fill text-success"></i>
                                    <small class="d-block text-muted">Tasks</small>
                                    <strong><?= $course->assignment_count ?? 0 ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="bi bi-file-earmark-fill text-warning"></i>
                                    <small class="d-block text-muted">Files</small>
                                    <strong><?= $course->material_count ?? 0 ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('teacher/edit_course/' . $course->id) ?>" class="btn btn-outline-primary"
                                    title="Edit Course">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?= base_url('teacher/students/' . $course->id) ?>" class="btn btn-outline-info"
                                    title="View Students">
                                    <i class="bi bi-people-fill"></i>
                                </a>
                                <a href="<?= base_url('teacher/materials/' . $course->id) ?>" class="btn btn-outline-success"
                                    title="Course Materials">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card border-0"
                style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="bi bi-journal-plus pulse"
                            style="font-size: 5rem; background: var(--success-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Ready to Create Your First Course? 🚀</h3>
                    <p class="text-muted mb-4 fs-5">Start your teaching journey by creating engaging courses for your
                        students.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= base_url('teacher/create_course') ?>" class="btn btn-success btn-lg px-4">
                            <i class="bi bi-plus-circle-fill me-2"></i>Create Your First Course
                        </a>
                        <button class="btn btn-outline-primary btn-lg px-4" data-bs-toggle="modal"
                            data-bs-target="#courseGuideModal">
                            <i class="bi bi-question-circle me-2"></i>Need Help?
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Course Guide Modal -->
<div class="modal fade" id="courseGuideModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-lightbulb me-2"></i>Course Creation Guide
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-1-circle-fill text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-2 fw-bold">Plan Your Course</h6>
                                <p class="small text-muted mb-0">Define learning objectives and course structure</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-2-circle-fill text-success" style="font-size: 2rem;"></i>
                                <h6 class="mt-2 fw-bold">Create Content</h6>
                                <p class="small text-muted mb-0">Add lessons, materials, and assignments</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-3-circle-fill text-warning" style="font-size: 2rem;"></i>
                                <h6 class="mt-2 fw-bold">Enroll Students</h6>
                                <p class="small text-muted mb-0">Invite students to join your course</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-4-circle-fill text-info" style="font-size: 2rem;"></i>
                                <h6 class="mt-2 fw-bold">Track Progress</h6>
                                <p class="small text-muted mb-0">Monitor student performance and engagement</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?= base_url('teacher/create_course') ?>" class="btn btn-primary">
                    <i class="bi bi-arrow-right me-2"></i>Start Creating
                </a>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
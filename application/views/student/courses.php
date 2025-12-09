<?php $this->load->view('templates/header', ['page_title' => 'My Courses']); ?>

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

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-book"></i> My Courses</h2>
    </div>
</div>

<!-- Enrolled Courses -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">Enrolled Courses</h4>
    </div>
    
    <?php if(isset($enrolled_courses) && count($enrolled_courses) > 0): ?>
        <?php foreach($enrolled_courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title"><?= $course->title ?></h5>
                            <span class="badge bg-success">Enrolled</span>
                        </div>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-code-square"></i> <?= $course->code ?></small>
                        </p>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-person-badge"></i> <?= $course->teacher_name ?></small>
                        </p>
                        <p class="card-text"><?= substr($course->description, 0, 100) ?>...</p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="<?= base_url('student/course_details/'.$course->id) ?>" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-eye"></i> View Course
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> You are not enrolled in any courses yet. Browse available courses below.
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Available Courses -->
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">Available Courses</h4>
    </div>
    
    <?php if(isset($available_courses) && count($available_courses) > 0): ?>
        <?php foreach($available_courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $course->title ?></h5>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-code-square"></i> <?= $course->code ?></small>
                        </p>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-person-badge"></i> <?= $course->teacher_name ?></small>
                        </p>
                        <p class="card-text"><?= substr($course->description, 0, 100) ?>...</p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="<?= base_url('student/enroll/'.$course->id) ?>" class="btn btn-sm btn-outline-success w-100" onclick="return confirm('Are you sure you want to enroll in this course?')">
                            <i class="bi bi-plus-circle"></i> Enroll Now
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-book" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">No Available Courses</h4>
                    <p class="text-muted">All courses are either enrolled or no courses are available at the moment.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $this->load->view('templates/footer'); ?>

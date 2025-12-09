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
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-journal-text"></i> My Courses</h2>
            <a href="<?= base_url('teacher/create_course') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Course
            </a>
        </div>
    </div>
</div>

<div class="row">
    <?php if(isset($courses) && count($courses) > 0): ?>
        <?php foreach($courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $course->title ?></h5>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-code-square"></i> <?= $course->code ?></small>
                        </p>
                        <p class="card-text"><?= substr($course->description, 0, 100) ?>...</p>
                        <p class="text-muted mb-0">
                            <small><i class="bi bi-calendar"></i> Created: <?= date('M d, Y', strtotime($course->created_at)) ?></small>
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('teacher/edit_course/'.$course->id) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="<?= base_url('teacher/students/'.$course->id) ?>" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-people"></i> Students
                            </a>
                            <a href="<?= base_url('teacher/materials/'.$course->id) ?>" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark"></i> Materials
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-journal-x" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">No Courses Yet</h4>
                    <p class="text-muted">You haven't created any courses. Start by creating your first course.</p>
                    <a href="<?= base_url('teacher/create_course') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Create Your First Course
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $this->load->view('templates/footer'); ?>

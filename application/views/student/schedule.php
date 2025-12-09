<?php $this->load->view('templates/header', ['page_title' => 'My Schedule']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-calendar3"></i> My Schedule</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Course Schedule</h5>
            </div>
            <div class="card-body">
                <?php if(isset($courses) && count($courses) > 0): ?>
                    <div class="list-group">
                        <?php foreach($courses as $course): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1"><?= $course->title ?></h5>
                                        <p class="mb-1 text-muted">
                                            <i class="bi bi-code-square"></i> <?= $course->code ?> |
                                            <i class="bi bi-person-badge"></i> <?= $course->teacher_name ?>
                                        </p>
                                        <small class="text-info">Schedule information will be available soon</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-success">Enrolled</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3">No Courses Enrolled</h4>
                        <p class="text-muted">You need to enroll in courses first to view your schedule.</p>
                        <a href="<?= base_url('student/courses') ?>" class="btn btn-primary">
                            <i class="bi bi-book"></i> Browse Courses
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

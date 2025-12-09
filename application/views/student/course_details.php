<?php $this->load->view('templates/header', ['page_title' => 'Course Details']); ?>

<div class="row mb-4">
    <div class="col-12">
        <a href="<?= base_url('student/courses') ?>" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Courses
        </a>
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= $course->title ?></h4>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Course Code:</strong> <?= $course->code ?></p>
                <p class="mb-2"><strong>Instructor:</strong> <?= $course->teacher_name ?></p>
                <p class="mb-0"><strong>Description:</strong></p>
                <p><?= $course->description ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Assignments</h5>
            </div>
            <div class="card-body">
                <?php if(isset($assignments) && count($assignments) > 0): ?>
                    <div class="list-group">
                        <?php foreach($assignments as $assignment): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= $assignment->title ?></h6>
                                    <small><?= date('M d, Y', strtotime($assignment->due_date)) ?></small>
                                </div>
                                <p class="mb-1 text-muted"><small><?= substr($assignment->description, 0, 80) ?>...</small></p>
                                <small>Max Points: <?= $assignment->max_points ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No assignments available for this course.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Course Materials</h5>
            </div>
            <div class="card-body">
                <?php if(isset($materials) && count($materials) > 0): ?>
                    <div class="list-group">
                        <?php foreach($materials as $material): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="bi bi-file-earmark"></i> <?= $material->title ?>
                                        </h6>
                                        <?php if($material->description): ?>
                                            <p class="mb-1 text-muted"><small><?= $material->description ?></small></p>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= base_url('uploads/materials/'.$material->file_name) ?>" class="btn btn-sm btn-outline-primary" download>
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No materials uploaded for this course yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

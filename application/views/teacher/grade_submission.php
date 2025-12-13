<?php $this->load->view('templates/header', ['page_title' => 'Grade Submission']); ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-pencil-square"></i> Grade Submission</h2>
                <p class="text-muted mb-0"><?= $submission->assignment_title ?></p>
            </div>
            <?php 
            $return_url = $this->input->get('return');
            if ($return_url === 'stats') {
                $back_url = base_url('teacher/assignment_stats/' . $submission->assignment_id);
            } else {
                $back_url = base_url('teacher/assignment_submissions/' . $submission->assignment_id);
            }
            ?>
            <a href="<?= $back_url ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Student & Assignment Info -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Student Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= $submission->student_name ?></p>
                <p><strong>Submitted:</strong> <?= date('F j, Y g:i A', strtotime($submission->submitted_at)) ?></p>
                <?php if ($submission->file_name): ?>
                    <p><strong>File:</strong> 
                        <a href="<?= base_url($submission->file_path) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i> <?= $submission->file_name ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-file-text"></i> Assignment Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Course:</strong> <?= $course->title ?> (<?= $course->code ?>)</p>
                <p><strong>Max Points:</strong> <?= $submission->max_points ?></p>
                <?php if ($submission->score !== null): ?>
                    <p><strong>Current Grade:</strong> 
                        <span class="badge bg-info"><?= $submission->score ?> / <?= $submission->max_points ?></span>
                        <small class="text-muted">(<?= number_format(($submission->score / $submission->max_points) * 100, 1) ?>%)</small>
                    </p>
                <?php else: ?>
                    <p><strong>Status:</strong> <span class="badge bg-warning">Not Graded</span></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Grading Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-star"></i> Grade Assignment</h5>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php echo form_open('teacher/grade_submission/' . $submission->id . ($this->input->get('return') ? '?return=' . $this->input->get('return') : '')); ?>
                    <div class="mb-3">
                        <label for="score" class="form-label">Score <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="score" 
                                   name="score" 
                                   min="0" 
                                   max="<?= $submission->max_points ?>" 
                                   step="0.01"
                                   value="<?= $submission->score ?? '' ?>" 
                                   required>
                            <span class="input-group-text">/ <?= $submission->max_points ?></span>
                        </div>
                        <small class="text-muted">Enter a score between 0 and <?= $submission->max_points ?></small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Feedback</label>
                        <textarea class="form-control" 
                                  id="feedback" 
                                  name="feedback" 
                                  rows="5" 
                                  placeholder="Provide feedback to the student..."><?= $submission->feedback ?? '' ?></textarea>
                        <small class="text-muted">Optional: Provide constructive feedback to help the student improve</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Grade
                        </button>
                        <a href="<?= $back_url ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

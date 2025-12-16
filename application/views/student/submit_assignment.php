<?php $this->load->view('templates/header', ['page_title' => 'Submit Assignment']); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <a href="<?= base_url('student/assignments') ?>" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Assignments
        </a>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><?= $assignment->title ?></h5>
            </div>
            <div class="card-body">
                <p><strong>Course:</strong> <?= $assignment->course_title ?></p>
                <p><strong>Due Date:</strong> <?= date('F d, Y - h:i A', strtotime($assignment->due_date)) ?></p>
                <p><strong>Max Points:</strong> <?= $assignment->max_points ?></p>
                <hr>
                <p><strong>Description:</strong></p>
                <p><?= nl2br($assignment->description) ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-upload"></i> Submit Your Work</h5>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= form_open_multipart('student/submit_assignment/' . $assignment->id) ?>
                <div class="mb-3">
                    <label for="file" class="form-label">Select File to Submit *</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                    <small class="text-muted">Allowed: PDF, DOC, DOCX, TXT, ZIP (Max: 5MB)</small>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Important:</strong> Make sure your file is
                    correct before submitting. You can resubmit if needed.
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('student/assignments') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Submit Assignment
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
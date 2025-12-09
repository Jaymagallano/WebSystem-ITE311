<?php $this->load->view('templates/header', ['page_title' => 'Create Course']); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Course</h5>
            </div>
            <div class="card-body">
                <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
                
                <form method="post" action="<?= base_url('teacher/create_course') ?>">
                    <div class="mb-3">
                        <label for="title" class="form-label">Course Title *</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Course Code *</label>
                        <input type="text" class="form-control" id="code" name="code" value="<?= set_value('code') ?>" placeholder="e.g., CS101" required>
                        <small class="text-muted">A unique identifier for this course</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= set_value('description') ?></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('teacher/courses') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Create Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

<?php $this->load->view('templates/header', ['page_title' => 'Edit Assignment']); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Assignment</h5>
            </div>
            <div class="card-body">
                <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
                
                <form method="post" action="<?= base_url('teacher/edit_assignment/' . ($assignment->id ?? '')) ?>">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select class="form-select" id="course_id" name="course_id" disabled>
                            <option value="<?= $assignment->course_id ?? '' ?>"><?= ($assignment->course_code ?? '') ?> - <?= ($assignment->course_title ?? '') ?></option>
                        </select>
                        <!-- Hidden input to preserve course_id -->
                        <input type="hidden" name="course_id" value="<?= $assignment->course_id ?? '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Assignment Title *</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $assignment->title ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= set_value('description', $assignment->description ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date *</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date" value="<?= set_value('due_date', date('Y-m-d\TH:i', strtotime($assignment->due_date ?? ''))) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_points" class="form-label">Max Points *</label>
                                <input type="number" class="form-control" id="max_points" name="max_points" value="<?= set_value('max_points', $assignment->max_points ?? '') ?>" min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('teacher/assignments') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
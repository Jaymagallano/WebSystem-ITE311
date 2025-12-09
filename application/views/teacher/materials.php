<?php $this->load->view('templates/header', ['page_title' => 'Course Materials']); ?>

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
            <h2><i class="bi bi-file-earmark-text"></i> Course Materials</h2>
            <?php if(isset($selected_course)): ?>
                <a href="<?= base_url('teacher/upload_material') ?>?course_id=<?= $selected_course->id ?>" class="btn btn-primary">
                    <i class="bi bi-cloud-upload"></i> Upload Material
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(isset($courses) && count($courses) > 0): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="courseFilter" class="form-label">Select Course:</label>
            <select class="form-select" id="courseFilter" onchange="window.location.href='<?= base_url('teacher/materials/') ?>' + this.value">
                <option value="">Choose a course...</option>
                <?php foreach($courses as $course): ?>
                    <option value="<?= $course->id ?>" <?= isset($selected_course) && $selected_course->id == $course->id ? 'selected' : '' ?>>
                        <?= $course->code ?> - <?= $course->title ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($selected_course)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?= $selected_course->title ?> - Materials</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($materials) && count($materials) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>File Type</th>
                                        <th>Uploaded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($materials as $material): ?>
                                        <tr>
                                            <td>
                                                <i class="bi bi-file-earmark"></i> <strong><?= $material->title ?></strong>
                                                <?php if($material->description): ?>
                                                    <br><small class="text-muted"><?= $material->description ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?= strtoupper(str_replace('.', '', $material->file_type)) ?></span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($material->created_at)) ?></td>
                                                                                        <td>
                                                <a href="<?= base_url('uploads/materials/'.$material->file_name) ?>" class="btn btn-sm btn-outline-primary" download>
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?= $material->id ?>">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                                
                                                <!-- Delete Confirmation Modal -->
                                                <div class="modal fade" id="deleteModal<?= $material->id ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete this material?</p>
                                                                <p><strong><?= $material->title ?></strong></p>
                                                                <p class="text-muted">This action cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <a href="<?= base_url('teacher/delete_material/' . $material->id) ?>" class="btn btn-danger">
                                                                    <i class="bi bi-trash"></i> Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-2 text-muted">No materials uploaded for this course yet.</p>
                            <a href="<?= base_url('teacher/upload_material') ?>?course_id=<?= $selected_course->id ?>" class="btn btn-primary">
                                <i class="bi bi-cloud-upload"></i> Upload First Material
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-file-earmark-text" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Select a Course</h4>
                    <p class="text-muted">Choose a course from the dropdown above to view and manage materials.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>

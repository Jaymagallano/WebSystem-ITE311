<?php $this->load->view('templates/header', ['page_title' => 'Upload Material']); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cloud-upload"></i> Upload Course Material</h5>
            </div>
            <div class="card-body">
                <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
                
                <form method="post" action="<?= base_url('teacher/upload_material') ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Select Course *</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Choose a course...</option>
                            <?php if(isset($courses) && count($courses) > 0): ?>
                                <?php foreach($courses as $course): ?>
                                    <option value="<?= $course->id ?>" <?= set_select('course_id', $course->id, isset($_GET['course_id']) && $_GET['course_id'] == $course->id) ?>>
                                        <?= $course->code ?> - <?= $course->title ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Material Title *</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= set_value('description') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File *</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <small class="text-muted">Allowed: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG (Max: 10MB)</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('teacher/materials') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload"></i> Upload Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

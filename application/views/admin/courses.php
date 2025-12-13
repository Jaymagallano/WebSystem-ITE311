<?php $this->load->view('templates/header', ['page_title' => 'Manage Courses']); ?>

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
        <div class="card" style="background: var(--primary-color); color: white; border: none;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1 fw-normal">
                            <i class="bi bi-book me-2"></i>Course Management
                        </h3>
                        <p class="mb-0 small">Manage all courses in the system</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course Code</th>
                                <th>Title</th>
                                <th>Teacher</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($courses) && count($courses) > 0): ?>
                                <?php foreach($courses as $course): ?>
                                    <tr>
                                        <td><?= $course->id ?></td>
                                        <td><span class="badge bg-info"><?= $course->code ?></span></td>
                                        <td><strong><?= $course->title ?></strong></td>
                                        <td>
                                            <i class="bi bi-person-badge"></i> <?= $course->teacher_name ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($course->created_at)) ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?= $course->id ?>">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="<?= base_url('admin/delete_course/'.$course->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this course? This will also delete all related data.')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-book" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">No courses found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modals -->
<?php if(isset($courses) && count($courses) > 0): ?>
    <?php foreach($courses as $course): ?>
        <div class="modal fade" id="viewModal<?= $course->id ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $course->id ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel<?= $course->id ?>"><?= $course->title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-code-square"></i> Course Code:</strong>
                                <p><?= $course->code ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-person-badge"></i> Teacher:</strong>
                                <p><?= $course->teacher_name ?></p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong><i class="bi bi-file-text"></i> Description:</strong>
                            <p class="text-muted"><?= !empty($course->description) ? $course->description : 'No description available' ?></p>
                        </div>
                        <div class="mb-3">
                            <strong><i class="bi bi-calendar-event"></i> Created:</strong>
                            <p><?= date('F d, Y \a\t g:i A', strtotime($course->created_at)) ?></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>

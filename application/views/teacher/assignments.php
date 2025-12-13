<?php $this->load->view('templates/header', ['page_title' => 'Assignments']); ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
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
                            <i class="bi bi-clipboard me-2"></i>Assignment Management
                        </h3>
                        <p class="mb-0 small">Create and track student assignments</p>
                    </div>
                    <div>
                        <a href="<?= base_url('teacher/create_assignment') ?>" class="btn btn-light">
                            <i class="bi bi-plus me-2"></i>New Assignment
                        </a>
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
                                <th class="fw-bold"><i class="bi bi-card-text me-2"></i>Title</th>
                                <th class="fw-bold"><i class="bi bi-book me-2"></i>Course</th>
                                <th class="fw-bold"><i class="bi bi-calendar-event me-2"></i>Due Date</th>
                                <th class="fw-bold"><i class="bi bi-award me-2"></i>Max Points</th>
                                <th class="fw-bold"><i class="bi bi-clock me-2"></i>Created</th>
                                <th class="fw-bold"><i class="bi bi-gear me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($assignments) && count($assignments) > 0): ?>
                                <?php foreach($assignments as $assignment): ?>
                                    <tr class="assignment-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="assignment-icon me-3" style="width: 40px; height: 40px; background: var(--primary-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                                                    <i class="bi bi-clipboard-check-fill"></i>
                                                </div>
                                                <div>
                                                    <strong class="fw-semibold"><?= $assignment->title ?></strong>
                                                    <br><small class="text-muted">Assignment</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge" style="background: var(--success-gradient); color: white;"><?= $assignment->course_code ?></span>
                                                <div class="mt-1 fw-medium"><?= $assignment->course_title ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                            $due_date = strtotime($assignment->due_date);
                                            $is_overdue = $due_date < time();
                                            $days_left = ceil(($due_date - time()) / (60 * 60 * 24));
                                            ?>
                                            <div class="<?= $is_overdue ? 'text-danger' : ($days_left <= 3 ? 'text-warning' : 'text-success') ?>">
                                                <i class="bi bi-calendar-event"></i> <?= date('M d, Y', $due_date) ?>
                                                <br><small class="fw-medium">
                                                    <?php if($is_overdue): ?>
                                                        <i class="bi bi-exclamation-triangle"></i> Overdue
                                                    <?php elseif($days_left <= 3): ?>
                                                        <i class="bi bi-clock"></i> <?= $days_left ?> days left
                                                    <?php else: ?>
                                                        <i class="bi bi-check-circle"></i> On time
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span class="badge bg-warning text-dark fs-6 px-3 py-2"><?= $assignment->max_points ?> pts</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($assignment->created_at)) ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('teacher/edit_assignment/' . $assignment->id) ?>" class="btn btn-sm btn-outline-primary" title="Edit Assignment">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="<?= base_url('teacher/assignment_submissions/' . $assignment->id) ?>" class="btn btn-sm btn-outline-success" title="View Submissions">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <a href="<?= base_url('teacher/assignment_stats/' . $assignment->id) ?>" class="btn btn-sm btn-outline-info" title="Assignment Stats">
                                                    <i class="bi bi-graph-up"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bi bi-clipboard-plus pulse" style="font-size: 4rem; background: var(--warning-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                                            <h5 class="mt-3 fw-bold">No Assignments Yet</h5>
                                            <p class="text-muted mb-3">Create your first assignment to get started!</p>
                                            <a href="<?= base_url('teacher/create_assignment') ?>" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Create Assignment
                                            </a>
                                        </div>
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

<?php $this->load->view('templates/footer'); ?>

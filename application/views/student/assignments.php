<?php $this->load->view('templates/header', ['page_title' => 'Assignments']); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: var(--primary-color); color: white; border: none;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-1 fw-normal">
                            <i class="bi bi-clipboard me-2"></i>My Assignments
                        </h3>
                        <p class="mb-0 small">View and submit your assignments</p>
                    </div>
                    <div>
                        <i class="bi bi-clipboard-data-fill" style="font-size: 4rem; opacity: 0.3;"></i>
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
                                <th class="fw-bold"><i class="bi bi-clipboard-text me-2"></i>Assignment</th>
                                <th class="fw-bold"><i class="bi bi-book me-2"></i>Course</th>
                                <th class="fw-bold"><i class="bi bi-calendar-event me-2"></i>Due Date</th>
                                <th class="fw-bold"><i class="bi bi-award me-2"></i>Points</th>
                                <th class="fw-bold"><i class="bi bi-flag me-2"></i>Status</th>
                                <th class="fw-bold"><i class="bi bi-star me-2"></i>Score</th>
                                <th class="fw-bold"><i class="bi bi-gear me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($assignments) && count($assignments) > 0): ?>
                                <?php foreach ($assignments as $assignment): ?>
                                    <?php
                                    $due_date = strtotime($assignment->due_date);
                                    $is_overdue = $due_date < time();
                                    $is_submitted = !empty($assignment->submission_id);
                                    $is_graded = !empty($assignment->score);
                                    ?>
                                    <tr class="assignment-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="assignment-icon me-3"
                                                    style="width: 40px; height: 40px; background: <?= $is_submitted ? 'var(--success-gradient)' : 'var(--warning-gradient)' ?>; border-radius: 0px; display: flex; align-items: center; justify-content: center; color: white;">
                                                    <i
                                                        class="bi bi-<?= $is_submitted ? 'check-circle-fill' : 'clock-fill' ?>"></i>
                                                </div>
                                                <div>
                                                    <strong class="fw-semibold"><?= $assignment->title ?></strong>
                                                    <br><small
                                                        class="text-muted"><?= substr($assignment->description, 0, 60) ?>...</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge"
                                                    style="background: var(--primary-gradient); color: white;"><?= $assignment->course_code ?></span>
                                                <div class="mt-1 fw-medium"><?= $assignment->course_title ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $days_left = ceil(($due_date - time()) / (60 * 60 * 24));
                                            ?>
                                            <div
                                                class="<?= $is_overdue && !$is_submitted ? 'text-danger' : ($days_left <= 3 && !$is_submitted ? 'text-warning' : 'text-success') ?>">
                                                <i class="bi bi-calendar-event"></i> <?= date('M d, Y', $due_date) ?>
                                                <br><small class="fw-medium">
                                                    <?php if ($is_overdue && !$is_submitted): ?>
                                                        <i class="bi bi-exclamation-triangle"></i> Overdue
                                                    <?php elseif ($days_left <= 3 && !$is_submitted): ?>
                                                        <i class="bi bi-clock"></i> <?= $days_left ?> days left
                                                    <?php elseif ($is_submitted): ?>
                                                        <i class="bi bi-check-circle"></i> Submitted
                                                    <?php else: ?>
                                                        <i class="bi bi-calendar-check"></i> On time
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span
                                                    class="badge bg-warning text-dark fs-6 px-3 py-2"><?= $assignment->max_points ?>
                                                    pts</span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($is_submitted): ?>
                                                <span class="badge"
                                                    style="background: var(--success-gradient); color: white; padding: 8px 12px;">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Submitted
                                                </span>
                                                <?php if ($assignment->submitted_at): ?>
                                                    <br><small
                                                        class="text-muted"><?= date('M d, Y', strtotime($assignment->submitted_at)) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge"
                                                    style="background: var(--warning-gradient); color: white; padding: 8px 12px;">
                                                    <i class="bi bi-clock-fill me-1"></i>Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($is_graded): ?>
                                                <?php
                                                $percentage = ($assignment->score / $assignment->max_points) * 100;
                                                $grade_color = $percentage >= 80 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger');
                                                ?>
                                                <div class="text-center">
                                                    <span
                                                        class="badge bg-<?= $grade_color ?> fs-6 px-3 py-2"><?= $assignment->score ?>
                                                        / <?= $assignment->max_points ?></span>
                                                    <br><small class="text-<?= $grade_color ?>"><?= round($percentage) ?>%</small>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-center text-muted">
                                                    <i class="bi bi-dash-circle" style="font-size: 1.5rem;"></i>
                                                    <br><small>Not graded</small>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$is_submitted): ?>
                                                <a href="<?= base_url('student/submit_assignment/' . $assignment->id) ?>"
                                                    class="btn btn-primary btn-sm px-3">
                                                    <i class="bi bi-upload me-1"></i>Submit
                                                </a>
                                            <?php else: ?>
                                                <div class="text-center">
                                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                                    <br><small class="text-success fw-medium">Complete</small>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bi bi-clipboard-heart pulse"
                                                style="font-size: 4rem; background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                                            <h5 class="mt-3 fw-bold">All Caught Up! 🎉</h5>
                                            <p class="text-muted mb-3">No assignments available right now. Check back later
                                                for new tasks!</p>
                                            <button class="btn btn-outline-primary" onclick="window.location.reload()">
                                                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                                            </button>
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
<?php $this->load->view('templates/header', ['page_title' => 'Assignments']); ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-clipboard-check"></i> My Assignments</h2>
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
                                <th>Assignment</th>
                                <th>Course</th>
                                <th>Due Date</th>
                                <th>Max Points</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($assignments) && count($assignments) > 0): ?>
                                <?php foreach($assignments as $assignment): ?>
                                    <?php
                                    $due_date = strtotime($assignment->due_date);
                                    $is_overdue = $due_date < time();
                                    $is_submitted = !empty($assignment->submission_id);
                                    $is_graded = !empty($assignment->score);
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?= $assignment->title ?></strong>
                                            <br><small class="text-muted"><?= substr($assignment->description, 0, 60) ?>...</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?= $assignment->course_code ?></span>
                                            <br><small><?= $assignment->course_title ?></small>
                                        </td>
                                        <td>
                                            <span class="<?= $is_overdue && !$is_submitted ? 'text-danger' : '' ?>">
                                                <i class="bi bi-calendar"></i> <?= date('M d, Y', $due_date) ?>
                                                <?php if($is_overdue && !$is_submitted): ?>
                                                    <br><small class="text-danger">Overdue</small>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td><?= $assignment->max_points ?> pts</td>
                                        <td>
                                            <?php if($is_submitted): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Submitted</span>
                                                <?php if($assignment->submitted_at): ?>
                                                    <br><small class="text-muted"><?= date('M d, Y', strtotime($assignment->submitted_at)) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-warning"><i class="bi bi-clock"></i> Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($is_graded): ?>
                                                <span class="badge bg-primary"><?= $assignment->score ?> / <?= $assignment->max_points ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(!$is_submitted): ?>
                                                <a href="<?= base_url('student/submit_assignment/'.$assignment->id) ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-upload"></i> Submit
                                                </a>
                                            <?php else: ?>
                                                <span class="text-success"><i class="bi bi-check-lg"></i> Done</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-clipboard-x" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">No assignments available at this time.</p>
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

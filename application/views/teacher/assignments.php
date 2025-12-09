<?php $this->load->view('templates/header', ['page_title' => 'Assignments']); ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-clipboard-check"></i> Assignments</h2>
            <a href="<?= base_url('teacher/create_assignment') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create Assignment
            </a>
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
                                <th>Title</th>
                                <th>Course</th>
                                <th>Due Date</th>
                                <th>Max Points</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($assignments) && count($assignments) > 0): ?>
                                <?php foreach($assignments as $assignment): ?>
                                    <tr>
                                        <td><strong><?= $assignment->title ?></strong></td>
                                        <td>
                                            <span class="badge bg-info"><?= $assignment->course_code ?></span>
                                            <?= $assignment->course_title ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $due_date = strtotime($assignment->due_date);
                                            $is_overdue = $due_date < time();
                                            ?>
                                            <span class="<?= $is_overdue ? 'text-danger' : '' ?>">
                                                <i class="bi bi-calendar"></i> <?= date('M d, Y', $due_date) ?>
                                            </span>
                                        </td>
                                        <td><?= $assignment->max_points ?> pts</td>
                                        <td><?= date('M d, Y', strtotime($assignment->created_at)) ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                                                                        <a href="<?= base_url('teacher/assignment_submissions/' . $assignment->id) ?>" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-eye"></i> View Submissions
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-clipboard-x" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">No assignments created yet.</p>
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

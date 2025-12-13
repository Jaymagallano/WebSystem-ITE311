<?php $this->load->view('templates/header', ['page_title' => 'Assignment Statistics']); ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-graph-up"></i> Assignment Statistics</h2>
                <p class="text-muted mb-0"><?= $assignment->title ?></p>
            </div>
            <a href="<?= base_url('teacher/assignments') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Assignments
            </a>
        </div>
    </div>
</div>

<!-- Course Info -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= $course->title ?> (<?= $course->code ?>)</h5>
                <p class="mb-0"><strong>Due Date:</strong> <?= date('F j, Y g:i A', strtotime($assignment->due_date)) ?></p>
                <p class="mb-0"><strong>Max Points:</strong> <?= $assignment->max_points ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Submission Stats -->
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2"><?= $total_students ?></h3>
                <p class="text-muted mb-0">Total Students</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2"><?= $submitted_count ?></h3>
                <p class="text-muted mb-0">Submitted</p>
                <small class="text-success"><?= $total_students > 0 ? round(($submitted_count / $total_students) * 100, 1) : 0 ?>%</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-clock-fill text-warning" style="font-size: 2rem;"></i>
                <h3 class="mt-2"><?= $pending_count ?></h3>
                <p class="text-muted mb-0">Pending</p>
                <small class="text-warning"><?= $total_students > 0 ? round(($pending_count / $total_students) * 100, 1) : 0 ?>%</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-star-fill text-info" style="font-size: 2rem;"></i>
                <h3 class="mt-2"><?= $graded_count ?></h3>
                <p class="text-muted mb-0">Graded</p>
                <small class="text-info"><?= $submitted_count > 0 ? round(($graded_count / $submitted_count) * 100, 1) : 0 ?>%</small>
            </div>
        </div>
    </div>
</div>

<!-- Grade Statistics -->
<?php if ($graded_count > 0): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Grade Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="text-primary"><?= number_format($average_percentage, 1) ?>%</h4>
                        <p class="text-muted mb-0">Average Score</p>
                        <small>(<?= number_format($average_grade, 1) ?> / <?= $assignment->max_points ?>)</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="text-success"><?= number_format($highest_grade, 1) ?></h4>
                        <p class="text-muted mb-0">Highest Grade</p>
                        <small><?= $assignment->max_points > 0 ? number_format(($highest_grade / $assignment->max_points) * 100, 1) : 0 ?>%</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="text-danger"><?= number_format($lowest_grade, 1) ?></h4>
                        <p class="text-muted mb-0">Lowest Grade</p>
                        <small><?= $assignment->max_points > 0 ? number_format(($lowest_grade / $assignment->max_points) * 100, 1) : 0 ?>%</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="text-warning"><?= $ungraded_count ?></h4>
                        <p class="text-muted mb-0">Needs Grading</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No graded submissions yet. Grade statistics will appear once you start grading submissions.
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Submissions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Recent Submissions</h5>
                <a href="<?= base_url('teacher/assignment_submissions/' . $assignment->id) ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye"></i> View All Submissions
                </a>
            </div>
            <div class="card-body">
                <?php if (count($submissions) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $recent_submissions = array_slice($submissions, 0, 5);
                                foreach($recent_submissions as $submission): 
                                ?>
                                    <tr>
                                        <td><?= $submission->student_name ?></td>
                                        <td><?= date('M d, Y g:i A', strtotime($submission->submitted_at)) ?></td>
                                        <td>
                                            <?php if ($submission->status === 'graded'): ?>
                                                <span class="badge bg-success">Graded</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($submission->status === 'graded'): ?>
                                                <strong><?= $submission->points_earned ?> / <?= $assignment->max_points ?></strong>
                                                <small class="text-muted">(<?= number_format(($submission->points_earned / $assignment->max_points) * 100, 1) ?>%)</small>
                                            <?php else: ?>
                                                <span class="text-muted">Not graded</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('teacher/grade_submission/' . $submission->id . '?return=stats') ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i> Grade
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">No submissions yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

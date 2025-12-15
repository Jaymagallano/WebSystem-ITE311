<?php $this->load->view('templates/header', ['page_title' => 'Assignment Submissions']); ?>

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('teacher/assignments') ?>">Assignments</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $assignment->title ?></li>
            </ol>
        </nav>
        <h2><i class="bi bi-clipboard-check"></i> <?= $assignment->title ?></h2>
        <p class="text-muted"><?= $course->code ?> - <?= $course->title ?></p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6 class="text-muted">Due Date</h6>
                            <p class="h5">
                                <?= date('M d, Y', strtotime($assignment->due_date)) ?>
                                <br>
                                <small class="text-muted"><?= date('h:i A', strtotime($assignment->due_date)) ?></small>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6 class="text-muted">Max Points</h6>
                            <p class="h5"><?= $assignment->max_points ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6 class="text-muted">Total Students</h6>
                            <p class="h5"><?= $total_students ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6 class="text-muted">Submissions</h6>
                            <p class="h5">
                                <span class="badge bg-<?= $submitted_count > 0 ? 'success' : 'secondary' ?>">
                                    <?= $submitted_count ?> / <?= $total_students ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted">Description</h6>
                        <p><?= nl2br($assignment->description) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Student Submissions</h5>
                <span class="badge bg-primary"><?= $submitted_count ?> submission(s)</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Submitted At</th>
                                <th>File</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($submissions) > 0): ?>
                                <?php foreach($submissions as $submission): ?>
                                    <tr>
                                        <td>
                                            <i class="bi bi-person-circle"></i> 
                                            <strong><?= $submission->student_name ?></strong>
                                        </td>
                                        <td><?= $submission->student_email ?></td>
                                        <td>
                                            <?php if($submission->submitted_at): ?>
                                                <span class="text-success">
                                                    <?= date('M d, Y', strtotime($submission->submitted_at)) ?>
                                                    <br>
                                                    <small><?= date('h:i A', strtotime($submission->submitted_at)) ?></small>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission->file_name): ?>
                                                <a href="<?= base_url('teacher/download_submission/'.$submission->id) ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-file-earmark"></i> View File
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission->score !== null): ?>
                                                <strong><?= $submission->score ?></strong> / <?= $assignment->max_points ?>
                                                <br>
                                                <?php 
                                                $percentage = ($submission->score / $assignment->max_points) * 100;
                                                $badge_class = $percentage >= 75 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?= $badge_class ?>">
                                                    <?= number_format($percentage, 2) ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Not graded</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission->score !== null): ?>
                                                <span class="badge bg-info">
                                                    <i class="bi bi-check-circle-fill"></i> Graded
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock"></i> Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#gradeModal<?= $submission->id ?>">
                                                <i class="bi bi-pencil"></i> <?= $submission->score !== null ? 'Edit Grade' : 'Grade' ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <h5 class="mt-3">No Submissions Yet</h5>
                                        <p class="text-muted">Students haven't submitted this assignment yet.</p>
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

<div class="row mt-3">
    <div class="col-12">
        <a href="<?= base_url('teacher/assignments') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Assignments
        </a>
    </div>
</div>

<!-- Grade Modals -->
<?php if(count($submissions) > 0): ?>
    <?php foreach($submissions as $submission): ?>
        <div class="modal fade" id="gradeModal<?= $submission->id ?>" tabindex="-1" aria-labelledby="gradeModalLabel<?= $submission->id ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gradeModalLabel<?= $submission->id ?>">
                            <i class="bi bi-pencil-square"></i> Grade Submission - <?= $submission->student_name ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="<?= base_url('teacher/grade_submission/' . $submission->id) ?>">
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Student:</strong></label>
                                    <p><?= $submission->student_name ?> (<?= $submission->student_email ?>)</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Submitted At:</strong></label>
                                    <p>
                                        <?= date('M d, Y h:i A', strtotime($submission->submitted_at)) ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>Submitted File:</strong></label>
                                <?php 
                                // Fix file path - remove full Windows path if present
                                $clean_path = $submission->file_path;
                                if (strpos($clean_path, 'htdocs') !== false) {
                                    $clean_path = substr($clean_path, strpos($clean_path, 'htdocs') + 7);
                                    $clean_path = str_replace('\\', '/', $clean_path);
                                    $clean_path = ltrim($clean_path, '/');
                                    if (strpos($clean_path, 'ITE311-MAGALLANO/') === 0) {
                                        $clean_path = substr($clean_path, strlen('ITE311-MAGALLANO/'));
                                    }
                                }
                                ?>
                                <div>
                                    <a href="<?= base_url($clean_path) ?>" target="_blank" class="btn btn-outline-secondary">
                                        <i class="bi bi-file-earmark"></i> <?= $submission->file_name ?>
                                    </a>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label for="score<?= $submission->id ?>" class="form-label">
                                    Score <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="score<?= $submission->id ?>" 
                                       name="score" 
                                       min="0" 
                                       max="<?= $assignment->max_points ?>" 
                                       step="0.01"
                                       value="<?= $submission->score ?>"
                                       required>
                                <small class="text-muted">Maximum points: <?= $assignment->max_points ?></small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="feedback<?= $submission->id ?>" class="form-label">
                                    Feedback (Optional)
                                </label>
                                <textarea class="form-control" 
                                          id="feedback<?= $submission->id ?>" 
                                          name="feedback" 
                                          rows="4"
                                          placeholder="Provide feedback to the student..."><?= $submission->feedback ?></textarea>
                            </div>
                            
                            <?php if($submission->feedback): ?>
                                <div class="alert alert-info">
                                    <strong>Previous Feedback:</strong><br>
                                    <?= nl2br($submission->feedback) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>

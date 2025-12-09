<?php $this->load->view('templates/header', ['page_title' => 'Student Grades']); ?>

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('teacher/grades') ?>">Grades</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('teacher/grades/' . $course->id) ?>"><?= $course->code ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $student->name ?></li>
            </ol>
        </nav>
        <h2><i class="bi bi-person-circle"></i> <?= $student->name ?>'s Grades</h2>
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
                            <h6 class="text-muted">Student Email</h6>
                            <p class="h5"><?= $student->email ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6 class="text-muted">Total Assignments</h6>
                            <p class="h5"><?= count($submissions) ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6 class="text-muted">Graded</h6>
                            <p class="h5">
                                <?php 
                                $graded = 0;
                                foreach($submissions as $sub) {
                                    if ($sub->score !== null) $graded++;
                                }
                                echo $graded;
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h6 class="text-muted">Average Grade</h6>
                            <p class="h2">
                                <?php if($average_grade > 0): ?>
                                    <span class="badge bg-<?= $average_grade >= 75 ? 'success' : ($average_grade >= 60 ? 'warning' : 'danger') ?>">
                                        <?= number_format($average_grade, 2) ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Assignment Submissions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Due Date</th>
                                <th>Submitted</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($submissions) > 0): ?>
                                <?php foreach($submissions as $submission): ?>
                                    <tr>
                                        <td>
                                            <strong><?= $submission->assignment_title ?></strong>
                                        </td>
                                        <td>
                                            <?= date('M d, Y', strtotime($submission->due_date)) ?>
                                        </td>
                                        <td>
                                            <?php if($submission->submitted_at): ?>
                                                <span class="text-success">
                                                    <i class="bi bi-check-circle"></i> 
                                                    <?= date('M d, Y', strtotime($submission->submitted_at)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Not submitted</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission->score !== null): ?>
                                                <strong><?= $submission->score ?></strong> / <?= $submission->max_points ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span> / <?= $submission->max_points ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission->score !== null): ?>
                                                <?php 
                                                $percentage = ($submission->score / $submission->max_points) * 100;
                                                $badge_class = $percentage >= 75 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?= $badge_class ?>">
                                                    <?= number_format($percentage, 2) ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Not Graded</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission->score !== null): ?>
                                                <span class="badge bg-info">
                                                    <i class="bi bi-check-circle-fill"></i> Graded
                                                </span>
                                            <?php elseif($submission->submitted_at): ?>
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock"></i> Pending Review
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle"></i> Not Submitted
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission->submitted_at): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#gradeModal<?= $submission->id ?>">
                                                    <i class="bi bi-pencil"></i> <?= $submission->score !== null ? 'Edit' : 'Grade' ?>
                                                </button>
                                                
                                                <!-- Grade Modal -->
                                                <div class="modal fade" id="gradeModal<?= $submission->id ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Grade Submission</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form method="post" action="<?= base_url('teacher/grade_submission/' . $submission->id) ?>">
                                                                <input type="hidden" name="course_id" value="<?= $course->id ?>">
                                                                <input type="hidden" name="student_id" value="<?= $student->id ?>">
                                                                <input type="hidden" name="redirect_to" value="student_grades">
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label"><strong>Assignment:</strong></label>
                                                                        <p><?= $submission->assignment_title ?></p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label"><strong>Submitted File:</strong></label>
                                                                        <p>
                                                                            <a href="<?= base_url($submission->file_path) ?>" target="_blank">
                                                                                <i class="bi bi-file-earmark"></i> <?= $submission->file_name ?>
                                                                            </a>
                                                                        </p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="score<?= $submission->id ?>" class="form-label">
                                                                            Score <span class="text-danger">*</span>
                                                                        </label>
                                                                        <input type="number" 
                                                                               class="form-control" 
                                                                               id="score<?= $submission->id ?>" 
                                                                               name="score" 
                                                                               min="0" 
                                                                               max="<?= $submission->max_points ?>" 
                                                                               step="0.01"
                                                                               value="<?= $submission->score ?>"
                                                                               required>
                                                                        <small class="text-muted">Maximum points: <?= $submission->max_points ?></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="feedback<?= $submission->id ?>" class="form-label">
                                                                            Feedback (Optional)
                                                                        </label>
                                                                        <textarea class="form-control" 
                                                                                  id="feedback<?= $submission->id ?>" 
                                                                                  name="feedback" 
                                                                                  rows="3"><?= $submission->feedback ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="bi bi-save"></i> Save Grade
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No submissions found for this student.</td>
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
        <a href="<?= base_url('teacher/grades/' . $course->id) ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Course Grades
        </a>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

<?php $this->load->view('templates/header', ['page_title' => 'My Grades']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-bar-chart"></i> My Grades</h2>
    </div>
</div>

<?php if(isset($courses) && count($courses) > 0): ?>
    <div class="row mb-4">
        <?php foreach($courses as $course): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $course->title ?></h5>
                        <p class="text-muted"><small><?= $course->code ?></small></p>
                        <h3 class="text-success">--%</h3>
                        <p class="text-muted mb-0">Average Grade</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Grade Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Course</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Submitted</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($grades) && count($grades) > 0): ?>
                                <?php foreach($grades as $grade): ?>
                                    <?php
                                    $percentage = ($grade->score / $grade->max_points) * 100;
                                    $grade_class = $percentage >= 75 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger');
                                    ?>
                                    <tr>
                                        <td><strong><?= $grade->assignment_title ?></strong></td>
                                        <td>
                                            <span class="badge bg-info"><?= $grade->course_code ?></span>
                                            <br><small><?= $grade->course_title ?></small>
                                        </td>
                                        <td>
                                            <strong><?= $grade->score ?></strong> / <?= $grade->max_points ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $grade_class ?>">
                                                <?= number_format($percentage, 1) ?>%
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($grade->submitted_at)) ?></td>
                                        <td>
                                            <?php if($grade->feedback): ?>
                                                <button class="btn btn-sm btn-outline-info view-feedback-btn" 
                                                        data-feedback="<?= htmlspecialchars($grade->feedback) ?>"
                                                        data-assignment="<?= htmlspecialchars($grade->assignment_title) ?>">
                                                    <i class="bi bi-chat-text"></i> View
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-bar-chart" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">No grades available yet.</p>
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

<!-- Feedback Modal (Reusable) -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Teacher Feedback - <span id="assignmentName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="feedbackContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackButtons = document.querySelectorAll('.view-feedback-btn');
    const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
    
    feedbackButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const feedback = this.dataset.feedback;
            const assignmentName = this.dataset.assignment;
            
            // Update modal content
            document.getElementById('assignmentName').textContent = assignmentName;
            document.getElementById('feedbackContent').innerHTML = feedback.replace(/\n/g, '<br>');
            
            // Show modal
            feedbackModal.show();
        });
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>

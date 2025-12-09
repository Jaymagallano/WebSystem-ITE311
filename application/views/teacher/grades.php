<?php $this->load->view('templates/header', ['page_title' => 'Grades']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-calculator"></i> Grades Management</h2>
    </div>
</div>

<?php if(isset($courses) && count($courses) > 0): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="courseFilter" class="form-label">Select Course:</label>
            <select class="form-select" id="courseFilter" onchange="window.location.href='<?= base_url('teacher/grades/') ?>' + this.value">
                <option value="">Choose a course...</option>
                <?php foreach($courses as $course): ?>
                    <option value="<?= $course->id ?>" <?= isset($selected_course) && $selected_course->id == $course->id ? 'selected' : '' ?>>
                        <?= $course->code ?> - <?= $course->title ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($selected_course)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?= $selected_course->title ?> - Student Grades</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Average Grade</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($students) && count($students) > 0): ?>
                                    <?php foreach($students as $student): ?>
                                        <tr>
                                            <td><i class="bi bi-person-circle"></i> <?= $student->name ?></td>
                                            <td><?= $student->email ?></td>
                                            <td>
                                                <span class="badge bg-success">--%</span>
                                            </td>
                                                                                        <td>
                                                <a href="<?= base_url('teacher/student_grades/' . $selected_course->id . '/' . $student->id) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No students enrolled in this course.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calculator" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Select a Course</h4>
                    <p class="text-muted">Choose a course from the dropdown above to view and manage student grades.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>

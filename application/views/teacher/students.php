<?php $this->load->view('templates/header', ['page_title' => 'Students']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-people"></i> Students</h2>
    </div>
</div>

<?php if(isset($course)): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <strong>Course:</strong> <?= $course->title ?> (<?= $course->code ?>)
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($courses) && count($courses) > 0): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="courseFilter" class="form-label">Filter by Course:</label>
            <select class="form-select" id="courseFilter" onchange="window.location.href='<?= base_url('teacher/students/') ?>' + this.value">
                <option value="">All Students</option>
                <?php foreach($courses as $c): ?>
                    <option value="<?= $c->id ?>" <?= isset($course) && $course->id == $c->id ? 'selected' : '' ?>>
                        <?= $c->title ?> (<?= $c->code ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <?php if(isset($course)): ?>
                                    <th>Enrolled Date</th>
                                <?php else: ?>
                                    <th>Registered Date</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($students) && count($students) > 0): ?>
                                <?php foreach($students as $student): ?>
                                    <tr>
                                        <td><?= $student->id ?></td>
                                        <td><i class="bi bi-person-circle"></i> <?= $student->name ?></td>
                                        <td><?= $student->email ?></td>
                                        <td>
                                            <?php if(isset($course) && isset($student->enrolled_at)): ?>
                                                <?= date('M d, Y', strtotime($student->enrolled_at)) ?>
                                            <?php else: ?>
                                                <?= date('M d, Y', strtotime($student->created_at)) ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <?php if(isset($course)): ?>
                                            No students enrolled in this course yet.
                                        <?php else: ?>
                                            No students found.
                                        <?php endif; ?>
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

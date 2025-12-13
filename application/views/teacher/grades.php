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
            <select class="form-select" id="courseFilter">
                <option value="">Choose a course...</option>
                <?php foreach($courses as $course): ?>
                    <option value="<?= $course->id ?>" <?= ($course_filter ?? '') == $course->id ? 'selected' : '' ?>>
                        <?= $course->code ?> - <?= $course->title ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($selected_course)): ?>
    <div class="row" id="gradesTableContainer">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0" id="courseTitle"><?= $selected_course->title ?> - Student Grades</h5>
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
                            <tbody id="gradesTableBody">
                                <?php if(isset($students) && count($students) > 0): ?>
                                    <?php foreach($students as $student): ?>
                                        <tr>
                                            <td><i class="bi bi-person-circle"></i> <?= $student->name ?></td>
                                            <td><?= $student->email ?></td>
                                            <td>
                                                <?php if ($student->average_grade !== null): ?>
                                                    <?php 
                                                        $grade = $student->average_grade;
                                                        $badge_class = 'bg-success';
                                                        if ($grade < 60) {
                                                            $badge_class = 'bg-danger';
                                                        } elseif ($grade < 75) {
                                                            $badge_class = 'bg-warning';
                                                        } elseif ($grade < 85) {
                                                            $badge_class = 'bg-info';
                                                        }
                                                    ?>
                                                    <span class="badge <?= $badge_class ?>"><?= number_format($grade, 2) ?>%</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">No grades yet</span>
                                                <?php endif; ?>
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
    <div class="row" id="gradesTableContainer">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseFilter = document.getElementById('courseFilter');
    const gradesTableContainer = document.getElementById('gradesTableContainer');
    
    if (courseFilter) {
        courseFilter.addEventListener('change', function() {
            const courseId = this.value;
            
            if (!courseId) {
                // Show empty state
                gradesTableContainer.innerHTML = `
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-calculator" style="font-size: 4rem; color: #ccc;"></i>
                                <h4 class="mt-3">Select a Course</h4>
                                <p class="text-muted">Choose a course from the dropdown above to view and manage student grades.</p>
                            </div>
                        </div>
                    </div>
                `;
                // Update URL
                window.history.pushState({}, '', '<?= base_url('teacher/grades') ?>');
                return;
            }
            
            // Show loading state
            gradesTableContainer.innerHTML = `
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Loading grades...</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Perform AJAX request
            const formData = new FormData();
            formData.append('course_id', courseId);
            
            fetch('<?= base_url('teacher/grades_ajax') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update table
                    gradesTableContainer.innerHTML = `
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">${data.course_title} - Student Grades</h5>
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
                                                ${data.html}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Update URL without reload
                    window.history.pushState({}, '', '<?= base_url('teacher/grades?course_id=') ?>' + courseId);
                    
                    // Add fade-in animation
                    gradesTableContainer.style.opacity = '0';
                    setTimeout(() => {
                        gradesTableContainer.style.transition = 'opacity 0.3s';
                        gradesTableContainer.style.opacity = '1';
                    }, 10);
                } else {
                    alert('Error loading grades: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                gradesTableContainer.innerHTML = `
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-danger">Error Loading Grades</h4>
                                <p class="text-muted">An error occurred while loading the grades. Please try again.</p>
                            </div>
                        </div>
                    </div>
                `;
            });
        });
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>

<?php $this->load->view('templates/header', ['page_title' => 'Students']); ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-people"></i> Students</h2>
            <div class="d-flex gap-2 align-items-center">
                <div class="text-muted me-3" id="studentCount">
                    <i class="bi bi-person-check"></i> <span id="countNumber"><?= count($students) ?></span> student(s)
                    found
                </div>
                <div class="btn-group">
                    <a href="<?= base_url('teacher/export_students' . (isset($course) ? '?course_id=' . $course->id : '')) ?>"
                        class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($course)): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <strong>Course:</strong> <?= $course->title ?> (<?= $course->code ?>)
                <a href="<?= base_url('teacher/students') ?>" class="btn btn-sm btn-outline-primary float-end">
                    <i class="bi bi-arrow-left"></i> View All Students
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Search & Filter Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="<?= base_url('teacher/students') ?>" id="filterForm">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">
                                <i class="bi bi-search"></i> Search
                            </label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Name, ID, or Email..." value="<?= $search ?? '' ?>">
                        </div>

                        <!-- Course Filter -->
                        <?php if (isset($courses) && count($courses) > 0): ?>
                            <div class="col-md-3">
                                <label for="courseFilter" class="form-label">
                                    <i class="bi bi-book"></i> Course
                                </label>
                                <select class="form-select" id="courseFilter" name="course_id">
                                    <option value="">All Courses</option>
                                    <?php foreach ($courses as $c): ?>
                                        <option value="<?= $c->id ?>" <?= ($course_filter ?? '') == $c->id ? 'selected' : '' ?>>
                                            <?= $c->title ?> (<?= $c->code ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <!-- Sort By -->
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">
                                <i class="bi bi-sort-down"></i> Sort By
                            </label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="name" <?= ($sort_by ?? 'name') === 'name' ? 'selected' : '' ?>>Name</option>
                                <option value="email" <?= ($sort_by ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                                <?php if (isset($course)): ?>
                                    <option value="enrolled_date" <?= ($sort_by ?? '') === 'enrolled_date' ? 'selected' : '' ?>>Enrolled Date</option>
                                <?php else: ?>
                                    <option value="registered_date" <?= ($sort_by ?? '') === 'registered_date' ? 'selected' : '' ?>>Registered Date</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-2">
                            <label for="sort_order" class="form-label">Order</label>
                            <select class="form-select" id="sort_order" name="sort_order">
                                <option value="asc" <?= ($sort_order ?? 'asc') === 'asc' ? 'selected' : '' ?>>
                                    <i class="bi bi-sort-up"></i> A-Z
                                </option>
                                <option value="desc" <?= ($sort_order ?? '') === 'desc' ? 'selected' : '' ?>>
                                    <i class="bi bi-sort-down"></i> Z-A
                                </option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill"></i> Apply Filters
                            </button>
                            <a href="<?= base_url('teacher/students') ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-table"></i> Student List
                    <?php if ($search ?? ''): ?>
                        <span class="badge bg-info">Filtered</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <?php if (isset($course)): ?>
                                    <th>Enrolled Date</th>
                                <?php else: ?>
                                    <th>Registered Date</th>
                                <?php endif; ?>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <?php if (isset($students) && count($students) > 0): ?>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><strong>#<?= $student->id ?></strong></td>
                                        <td>
                                            <i class="bi bi-person-circle text-primary"></i>
                                            <strong><?= $student->name ?></strong>
                                        </td>
                                        <td>
                                            <i class="bi bi-envelope"></i>
                                            <?= $student->email ?>
                                        </td>
                                        <?php if (isset($course)): ?>
                                            <td>
                                                <i class="bi bi-calendar-check"></i>
                                                <?php if (isset($student->enrolled_at)): ?>
                                                    <?= date('M d, Y', strtotime($student->enrolled_at)) ?>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                        <?php else: ?>
                                            <td>
                                                <i class="bi bi-calendar-plus"></i>
                                                <?= date('M d, Y', strtotime($student->created_at)) ?>
                                            </td>
                                        <?php endif; ?>
                                        <td>
                                            <?php if (isset($course)): ?>
                                                <a href="<?= base_url('teacher/student_grades/' . $course->id . '/' . $student->id) ?>"
                                                    class="btn btn-sm btn-outline-primary" title="View Grades">
                                                    <i class="bi bi-bar-chart"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-info" title="View Details"
                                                    onclick="alert('Student ID: <?= $student->id ?>\nName: <?= $student->name ?>\nEmail: <?= $student->email ?>')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">
                                            <?php if ($search ?? ''): ?>
                                                No students found matching your search criteria.
                                            <?php elseif (isset($course)): ?>
                                                No students enrolled in this course yet.
                                            <?php else: ?>
                                                No students found.
                                            <?php endif; ?>
                                        </p>
                                        <?php if ($search ?? ''): ?>
                                            <a href="<?= base_url('teacher/students') ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-arrow-clockwise"></i> Clear Search
                                            </a>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const courseFilter = document.getElementById('courseFilter');
        const sortBySelect = document.getElementById('sort_by');
        const sortOrderSelect = document.getElementById('sort_order');
        const tableBody = document.getElementById('studentsTableBody');
        const countNumber = document.getElementById('countNumber');

        const baseUrl = '<?= base_url('teacher/students_ajax') ?>';

        // Instant filter on change
        if (courseFilter) {
            courseFilter.addEventListener('change', performAjaxSearch);
        }
        sortBySelect.addEventListener('change', performAjaxSearch);
        sortOrderSelect.addEventListener('change', performAjaxSearch);

        function performAjaxSearch() {

            // Prepare data
            const formData = new FormData();
            formData.append('search', searchInput.value);
            if (courseFilter) {
                formData.append('course_id', courseFilter.value);
            }
            formData.append('sort_by', sortBySelect.value);
            formData.append('sort_order', sortOrderSelect.value);
            if (window.csrf_token_name) {
                formData.append(window.csrf_token_name, window.csrf_hash);
            }

            // Build URL
            const url = baseUrl;

            // Perform AJAX request
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Update CSRF token for next request
                    if (data.csrf_token) {
                        window.csrf_hash = data.csrf_token;
                    }
                    
                    if (data.success) {
                        // Update table
                        tableBody.innerHTML = data.html;

                        // Update count
                        countNumber.textContent = data.count;

                        // Add fade-in animation
                        tableBody.style.opacity = '0';
                        setTimeout(() => {
                            tableBody.style.transition = 'opacity 0.3s';
                            tableBody.style.opacity = '1';
                        }, 10);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while searching. Please try again.');
                });
        }

        // Prevent form submission (use AJAX instead)
        document.getElementById('filterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            performAjaxSearch();
        });
    });
</script>

<?php $this->load->view('templates/footer'); ?>
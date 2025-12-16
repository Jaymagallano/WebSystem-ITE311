<?php $this->load->view('templates/header', ['page_title' => 'Resources']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-download"></i> Course Resources</h2>
    </div>
</div>

<?php if (isset($courses) && count($courses) > 0): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="courseFilter" class="form-label">Filter by Course:</label>
            <select class="form-select" id="courseFilter">
                <option value="">Choose a course...</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course->id ?>" <?= ($course_filter ?? '') == $course->id ? 'selected' : '' ?>>
                        <?= $course->code ?> - <?= $course->title ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($selected_course)): ?>
    <div class="row" id="resourcesContainer">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0" id="courseTitle"><?= $selected_course->title ?> - Materials</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($materials) && count($materials) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Type</th>
                                        <th>Uploaded</th>
                                        <th>Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materials as $material): ?>
                                        <tr>
                                            <td>
                                                <i class="bi bi-file-earmark"></i> <strong><?= $material->title ?></strong>
                                                <?php if ($material->description): ?>
                                                    <br><small class="text-muted"><?= $material->description ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary"><?= strtoupper(str_replace('.', '', $material->file_type)) ?></span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($material->created_at)) ?></td>
                                            <td>
                                                <a href="<?= base_url('student/download_material/' . $material->id) ?>"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-2 text-muted">No materials available for this course yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row" id="resourcesContainer">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (isset($courses) && count($courses) > 0): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-download" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="mt-3">Select a Course</h4>
                            <p class="text-muted">Choose a course from the dropdown above to view available resources.</p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="mt-3">No Courses Enrolled</h4>
                            <p class="text-muted">You need to enroll in courses first to access resources.</p>
                            <a href="<?= base_url('student/courses') ?>" class="btn btn-primary">
                                <i class="bi bi-book"></i> Browse Courses
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const courseFilter = document.getElementById('courseFilter');
        const resourcesContainer = document.getElementById('resourcesContainer');

        if (courseFilter) {
            courseFilter.addEventListener('change', function () {
                const courseId = this.value;

                if (!courseId) {
                    // Show empty state
                    resourcesContainer.innerHTML = `
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center py-5">
                                    <i class="bi bi-download" style="font-size: 4rem; color: #ccc;"></i>
                                    <h4 class="mt-3">Select a Course</h4>
                                    <p class="text-muted">Choose a course from the dropdown above to view available resources.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    // Update URL
                    window.history.pushState({}, '', '<?= base_url('student/resources') ?>');
                    return;
                }

                // Show loading state
                resourcesContainer.innerHTML = `
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Loading resources...</p>
                        </div>
                    </div>
                </div>
            `;

                // Perform AJAX request
                const formData = new FormData();
                formData.append('course_id', courseId);
                if (window.csrf_token_name) {
                    formData.append(window.csrf_token_name, window.csrf_hash);
                }

                fetch('<?= base_url('student/resources_ajax') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update resources display
                            resourcesContainer.innerHTML = `
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">${data.course_title} - Materials</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        ${data.html}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                            // Update URL without reload
                            window.history.pushState({}, '', '<?= base_url('student/resources?course_id=') ?>' + courseId);

                            // Add fade-in animation
                            resourcesContainer.style.opacity = '0';
                            setTimeout(() => {
                                resourcesContainer.style.transition = 'opacity 0.3s';
                                resourcesContainer.style.opacity = '1';
                            }, 10);
                        } else {
                            resourcesContainer.innerHTML = `
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                                    <h4 class="mt-3 text-danger">Error</h4>
                                    <p class="text-muted">${data.message}</p>
                                </div>
                            </div>
                        </div>
                    `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resourcesContainer.innerHTML = `
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-danger">Error Loading Resources</h4>
                                <p class="text-muted">An error occurred while loading the resources. Please try again.</p>
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
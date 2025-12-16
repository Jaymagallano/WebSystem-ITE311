<?php $this->load->view('templates/header', ['page_title' => 'Course Materials']); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-file-earmark-text"></i> Course Materials</h2>
            <?php if (isset($selected_course)): ?>
                <a href="<?= base_url('teacher/upload_material') ?>?course_id=<?= $selected_course->id ?>"
                    class="btn btn-primary">
                    <i class="bi bi-cloud-upload"></i> Upload Material
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (isset($courses) && count($courses) > 0): ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="courseFilter" class="form-label">Select Course:</label>
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
    <div class="row" id="materialsTableContainer">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" id="courseTitle"><?= $selected_course->title ?> - Materials</h5>
                    <a href="<?= base_url('teacher/upload_material') ?>?course_id=<?= $selected_course->id ?>"
                        class="btn btn-sm btn-primary" id="uploadBtn">
                        <i class="bi bi-cloud-upload"></i> Upload Material
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($materials) && count($materials) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>File Type</th>
                                        <th>Uploaded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="materialsTableBody">
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
                                                <a href="<?= base_url('uploads/materials/' . $material->file_name) ?>"
                                                    class="btn btn-sm btn-outline-primary" download>
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-material-btn"
                                                    data-material-id="<?= $material->id ?>"
                                                    data-material-title="<?= htmlspecialchars($material->title) ?>">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-2 text-muted">No materials uploaded for this course yet.</p>
                            <a href="<?= base_url('teacher/upload_material') ?>?course_id=<?= $selected_course->id ?>"
                                class="btn btn-primary">
                                <i class="bi bi-cloud-upload"></i> Upload First Material
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row" id="materialsTableContainer">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-file-earmark-text" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Select a Course</h4>
                    <p class="text-muted">Choose a course from the dropdown above to view and manage materials.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Delete Confirmation Modal (Reusable) -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this material?</p>
                <p><strong id="materialTitle"></strong></p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="post" style="display: inline;">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Course filter change handler with AJAX
        const courseFilter = document.getElementById('courseFilter');
        const materialsTableContainer = document.getElementById('materialsTableContainer');

        if (courseFilter) {
            courseFilter.addEventListener('change', function () {
                const courseId = this.value;

                if (!courseId) {
                    // Show empty state
                    materialsTableContainer.innerHTML = `
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-file-earmark-text" style="font-size: 4rem; color: #ccc;"></i>
                                <h4 class="mt-3">Select a Course</h4>
                                <p class="text-muted">Choose a course from the dropdown above to view and manage materials.</p>
                            </div>
                        </div>
                    </div>
                `;
                    // Update URL
                    window.history.pushState({}, '', '<?= base_url('teacher/materials') ?>');
                    return;
                }

                // Show loading state
                materialsTableContainer.innerHTML = `
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Loading materials...</p>
                        </div>
                    </div>
                </div>
            `;

                // Perform AJAX request
                const formData = new FormData();
                formData.append('course_id', courseId);
                formData.append(window.csrf_token_name, window.csrf_hash);

                fetch('<?= base_url('teacher/materials_ajax') ?>', {
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
                            materialsTableContainer.innerHTML = `
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">${data.course_title} - Materials</h5>
                                    <a href="<?= base_url('teacher/upload_material') ?>?course_id=${courseId}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-cloud-upload"></i> Upload Material
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>File Type</th>
                                                    <th>Uploaded</th>
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
                            window.history.pushState({}, '', '<?= base_url('teacher/materials?course_id=') ?>' + courseId);

                            // Add fade-in animation
                            materialsTableContainer.style.opacity = '0';
                            setTimeout(() => {
                                materialsTableContainer.style.transition = 'opacity 0.3s';
                                materialsTableContainer.style.opacity = '1';
                            }, 10);
                        } else {
                            alert('Error loading materials: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        materialsTableContainer.innerHTML = `
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-danger">Error Loading Materials</h4>
                                <p class="text-muted">An error occurred while loading the materials. Please try again.</p>
                            </div>
                        </div>
                    </div>
                `;
                    });
            });
        }

        // Delete modal code
        const deleteMaterialButtons = document.querySelectorAll('.delete-material-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteForm = document.getElementById('deleteForm');

        deleteMaterialButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const materialId = this.dataset.materialId;
                const materialTitle = this.dataset.materialTitle;

                console.log('Delete button clicked for material ID:', materialId);

                // Update modal content
                document.getElementById('materialTitle').textContent = materialTitle;

                // Update form action
                deleteForm.action = '<?= base_url('teacher/delete_material/') ?>' + materialId;

                console.log('Form action set to:', deleteForm.action);

                // Show modal
                deleteModal.show();
            });
        });

        // Add form submit handler for debugging
        deleteForm.addEventListener('submit', function (e) {
            console.log('Form submitting to:', this.action);
        });
    });
</script>

<?php $this->load->view('templates/footer'); ?>
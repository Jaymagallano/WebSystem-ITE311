<?php $this->load->view('templates/header', ['page_title' => 'My Courses']); ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-book"></i> My Courses</h2>
    </div>
</div>

<!-- Enrolled Courses -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">Enrolled Courses</h4>
    </div>
    
    <?php if(isset($enrolled_courses) && count($enrolled_courses) > 0): ?>
        <?php foreach($enrolled_courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title"><?= htmlspecialchars($course->title) ?></h5>
                            <span class="badge bg-success">Enrolled</span>
                        </div>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-code-square"></i> <?= htmlspecialchars($course->code) ?></small>
                        </p>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-person-badge"></i> <?= htmlspecialchars($course->teacher_name) ?></small>
                        </p>
                        <p class="card-text"><?= htmlspecialchars(substr($course->description, 0, 100)) ?>...</p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="<?= base_url('student/course_details/'.intval($course->id)) ?>" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-eye"></i> View Course
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> You are not enrolled in any courses yet. Browse available courses below.
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Available Courses -->
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">Available Courses</h4>
    </div>
    
    <?php if(isset($available_courses) && count($available_courses) > 0): ?>
        <?php foreach($available_courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course->title) ?></h5>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-code-square"></i> <?= htmlspecialchars($course->code) ?></small>
                        </p>
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-person-badge"></i> <?= htmlspecialchars($course->teacher_name) ?></small>
                        </p>
                        <p class="card-text"><?= htmlspecialchars(substr($course->description, 0, 100)) ?>...</p>
                    </div>
                    <div class="card-footer bg-white">
                        <!-- Secure enrollment form with CSRF protection -->
                        <form class="enrollment-form" data-course-id="<?= intval($course->id) ?>">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                            <input type="hidden" name="course_id" value="<?= intval($course->id) ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-success w-100 enroll-btn">
                                <i class="bi bi-plus-circle"></i> Enroll Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-book" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">No Available Courses</h4>
                    <p class="text-muted">All courses are either enrolled or no courses are available at the moment.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Processing enrollment...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Secure AJAX enrollment with CSRF protection
    const enrollmentForms = document.querySelectorAll('.enrollment-form');
    
    enrollmentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const courseId = this.dataset.courseId;
            const csrfToken = this.querySelector('input[name="<?= $this->security->get_csrf_token_name() ?>"]').value;
            const submitBtn = this.querySelector('.enroll-btn');
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enrolling...';
            
            // Show loading modal
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            loadingModal.show();
            
            // Make secure AJAX request
            fetch('<?= base_url("student/enroll_ajax") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'course_id': courseId,
                    '<?= $this->security->get_csrf_token_name() ?>': csrfToken
                })
            })
            .then(response => response.json())
            .then(data => {
                loadingModal.hide();
                
                if (data.success) {
                    // Update CSRF token
                    if (data.csrf_token) {
                        document.querySelectorAll('input[name="<?= $this->security->get_csrf_token_name() ?>"]')
                            .forEach(input => input.value = data.csrf_token);
                    }
                    
                    // Show success message and reload page
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message);
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Enroll Now';
                }
            })
            .catch(error => {
                loadingModal.hide();
                console.error('Enrollment error:', error);
                showAlert('danger', 'An error occurred. Please try again.');
                
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Enroll Now';
            });
        });
    });
    
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at the top of the page
        const container = document.querySelector('.row.mb-4');
        container.parentNode.insertBefore(alertDiv, container);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>
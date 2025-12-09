<?php $this->load->view('templates/header', ['page_title' => 'System Settings']); ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-gear"></i> System Settings</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">General Settings</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('admin/settings') ?>">
                    <div class="mb-3">
                        <label for="system_name" class="form-label">System Name</label>
                        <input type="text" class="form-control" id="system_name" name="system_name" value="Learning Management System">
                    </div>
                    
                    <div class="mb-3">
                        <label for="system_email" class="form-label">System Email</label>
                        <input type="email" class="form-control" id="system_email" name="system_email" value="admin@lms.com">
                    </div>
                    
                    <div class="mb-3">
                        <label for="timezone" class="form-label">Timezone</label>
                        <select class="form-select" id="timezone" name="timezone">
                            <option value="Asia/Manila" selected>Asia/Manila (GMT+8)</option>
                            <option value="UTC">UTC (GMT+0)</option>
                            <option value="America/New_York">America/New York (GMT-5)</option>
                        </select>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Email Notifications</h5>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notify_registration" name="notify_registration" checked>
                        <label class="form-check-label" for="notify_registration">
                            Send email on user registration
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notify_enrollment" name="notify_enrollment" checked>
                        <label class="form-check-label" for="notify_enrollment">
                            Send email on course enrollment
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notify_assignment" name="notify_assignment" checked>
                        <label class="form-check-label" for="notify_assignment">
                            Send email on new assignment
                        </label>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">System Limits</h5>
                    
                    <div class="mb-3">
                        <label for="max_file_size" class="form-label">Max File Upload Size (MB)</label>
                        <input type="number" class="form-control" id="max_file_size" name="max_file_size" value="10" min="1" max="100">
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_students_per_course" class="form-label">Max Students Per Course</label>
                        <input type="number" class="form-control" id="max_students_per_course" name="max_students_per_course" value="50" min="1">
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Maintenance</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Database Backup</h6>
                    <p class="text-muted">Create a backup of the database</p>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Backup Database
                    </button>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6>Clear Cache</h6>
                    <p class="text-muted">Clear all cached data</p>
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-trash"></i> Clear Cache
                    </button>
                </div>
                
                <hr>
                
                <div>
                    <h6 class="text-danger">Danger Zone</h6>
                    <p class="text-muted">Reset all data (this cannot be undone)</p>
                    <button class="btn btn-outline-danger" onclick="return confirm('Are you sure? This will delete all data!')">
                        <i class="bi bi-exclamation-triangle"></i> Reset System
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>

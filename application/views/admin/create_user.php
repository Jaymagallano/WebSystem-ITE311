<?php $this->load->view('templates/header', ['page_title' => 'Create User']); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-plus"></i> Create User</h5>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i> <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle-fill"></i> <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle-fill"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

                <?= form_open('admin/create_user') ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>"
                        required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted">Min 8 characters. Leave blank to auto-generate.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role *</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select Role...</option>
                        <?php if ($this->session->userdata('user_id') == 1): ?>
                            <option value="admin" <?= set_select('role', 'admin') ?>>Admin</option>
                        <?php endif; ?>
                        <option value="teacher" <?= set_select('role', 'teacher') ?>>Teacher</option>
                        <option value="student" <?= set_select('role', 'student') ?>>Student</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Create User
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
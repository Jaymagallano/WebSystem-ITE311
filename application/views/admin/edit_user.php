<?php $this->load->view('templates/header', ['page_title' => 'Edit User']); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit User</h5>
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

                <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

                <?= form_open('admin/edit_user/' . $edit_user->id) ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?= set_value('name', $edit_user->name) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" value="<?= $edit_user->email ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="text-muted">Leave blank to keep current password</small>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role *</label>
                    <select class="form-select" id="role" name="role" required <?= (isset($is_self_edit) && $is_self_edit) || (isset($disable_admin_role) && $disable_admin_role) ? 'data-restricted="true"' : '' ?>>
                        
                        <?php // Show Admin option ONLY if functionality warrants it (user is Admin OR Super Admin is editing) ?>
                        <?php if ($edit_user->role == 'admin' || $this->session->userdata('user_id') == 1): ?>
                            <option value="admin" <?= set_select('role', 'admin', $edit_user->role == 'admin') ?>
                                <?= isset($disable_admin_role) && $disable_admin_role ? 'disabled' : '' ?>>Admin</option>
                        <?php endif; ?>
                            
                        <?php // Show Teacher/Student options if functionality warrants it (user is NOT Admin OR Super Admin is editing) ?>
                        <?php if ($edit_user->role != 'admin' || $this->session->userdata('user_id') == 1): ?>
                            <option value="teacher" <?= set_select('role', 'teacher', $edit_user->role == 'teacher') ?>>Teacher</option>
                            <option value="student" <?= set_select('role', 'student', $edit_user->role == 'student') ?>>Student</option>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($is_self_edit) && $is_self_edit): ?>
                        <small class="text-muted">You cannot change your own role</small>
                    <?php elseif (isset($disable_admin_role) && $disable_admin_role): ?>
                        <small class="text-muted">Admin role is not available for teacher and student users</small>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update User
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
<?php $this->load->view('templates/header', ['page_title' => 'Manage Users']); ?>

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
        <div class="card" style="background: var(--primary-color); color: white; border: none;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1 fw-normal">
                            <i class="bi bi-people me-2"></i>User Management
                        </h3>
                        <p class="mb-0 small">Manage system users and their permissions</p>
                    </div>
                    <div>
                        <a href="<?= base_url('admin/create_user') ?>" class="btn btn-light">
                            <i class="bi bi-plus me-2"></i>Add User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="fw-bold"><i class="bi bi-hash me-2"></i>ID</th>
                                <th class="fw-bold"><i class="bi bi-person me-2"></i>Name</th>
                                <th class="fw-bold"><i class="bi bi-envelope me-2"></i>Email</th>
                                <th class="fw-bold"><i class="bi bi-shield me-2"></i>Role</th>
                                <th class="fw-bold"><i class="bi bi-calendar me-2"></i>Registered</th>
                                <th class="fw-bold"><i class="bi bi-gear me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($users) && count($users) > 0): ?>
                                <?php foreach($users as $user_item): ?>
                                    <tr>
                                        <td><?= $user_item->id ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                                    <?= strtoupper(substr($user_item->name, 0, 1)) ?>
                                                </div>
                                                <span class="fw-medium"><?= $user_item->name ?></span>
                                            </div>
                                        </td>
                                        <td><?= $user_item->email ?></td>
                                        <td>
                                            <?php if($user_item->role == 'admin'): ?>
                                                <span class="badge bg-danger">Admin</span>
                                            <?php elseif($user_item->role == 'teacher'): ?>
                                                <span class="badge bg-info">Teacher</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Student</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($user_item->created_at)) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('admin/edit_user/'.$user_item->id) ?>" class="btn btn-sm btn-outline-primary" title="Edit User">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <?php if($user_item->id != $this->session->userdata('user_id')): ?>
                                                    <a href="<?= base_url('admin/delete_user/'.$user_item->id) ?>" class="btn btn-sm btn-outline-danger" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No users found</td>
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

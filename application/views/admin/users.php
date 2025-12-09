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
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-people"></i> Manage Users</h2>
            <a href="<?= base_url('admin/create_user') ?>" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add New User
            </a>
        </div>
    </div>
</div>

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
                                <th>Role</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($users) && count($users) > 0): ?>
                                <?php foreach($users as $user_item): ?>
                                    <tr>
                                        <td><?= $user_item->id ?></td>
                                        <td><i class="bi bi-person-circle"></i> <?= $user_item->name ?></td>
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
                                            <a href="<?= base_url('admin/edit_user/'.$user_item->id) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <?php if($user_item->id != $this->session->userdata('user_id')): ?>
                                                <a href="<?= base_url('admin/delete_user/'.$user_item->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            <?php endif; ?>
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

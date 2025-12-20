<?php $this->load->view('templates/header', ['page_title' => 'Manage Users']); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Success!</h5>
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>


<!-- Action Buttons -->
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-end">
        <a href="<?= base_url('admin/create_user') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add User
        </a>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?= form_open('admin/users', ['method' => 'get', 'id' => 'userFilterForm', 'class' => 'row g-3 align-items-end']) ?>
                    <div class="col-md-6">
                        <label for="searchInput" class="form-label">
                            <i class="bi bi-search"></i> Search
                        </label>
                        <input type="text" class="form-control" id="searchInput" name="q"
                            placeholder="Search by name, email, or ID..."
                            value="<?= isset($filters['q']) ? html_escape($filters['q']) : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="roleFilter" class="form-label">
                            <i class="bi bi-shield"></i> Role
                        </label>
                        <select class="form-select" id="roleFilter" name="role">
                            <option value="" <?= empty($filters['role']) ? 'selected' : '' ?>>All Roles</option>
                            <option value="admin" <?= (isset($filters['role']) && $filters['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="teacher" <?= (isset($filters['role']) && $filters['role'] === 'teacher') ? 'selected' : '' ?>>Teacher</option>
                            <option value="student" <?= (isset($filters['role']) && $filters['role'] === 'student') ? 'selected' : '' ?>>Student</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="sortBy" class="form-label">
                            <i class="bi bi-sort-down"></i> Sort By
                        </label>
                        <select class="form-select" id="sortBy" name="sort">
                            <?php $currentSort = $filters['sort'] ?? 'date'; ?>
                            <option value="name" <?= $currentSort === 'name' ? 'selected' : '' ?>>Name</option>
                            <option value="email" <?= $currentSort === 'email' ? 'selected' : '' ?>>Email</option>
                            <option value="role" <?= $currentSort === 'role' ? 'selected' : '' ?>>Role</option>
                            <option value="date" <?= $currentSort === 'date' ? 'selected' : '' ?>>Date</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary w-100" id="applyServerFilter">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Showing <strong id="resultCount"><?= count($users) ?></strong> user(s)
                            <span class="ms-2 small text-secondary">(server + client filters)</span>
                        </span>
                    </div>
                <?= form_close() ?>
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
                        <tbody id="usersTableBody">
                            <?php if (isset($users) && count($users) > 0): ?>
                                <?php foreach ($users as $user_item): ?>
                                    <tr class="user-row" data-id="<?= $user_item->id ?>"
                                        data-name="<?= strtolower($user_item->name) ?>"
                                        data-email="<?= strtolower($user_item->email) ?>" data-role="<?= $user_item->role ?>"
                                        data-date="<?= strtotime($user_item->created_at) ?>">
                                        <td><?= $user_item->id ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2"
                                                    style="width: 32px; height: 32px; background: var(--primary-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                                    <?= strtoupper(substr($user_item->name, 0, 1)) ?>
                                                </div>
                                                <span class="fw-medium"><?= html_escape($user_item->name) ?></span>
                                            </div>
                                        </td>
                                        <td><?= $user_item->email ?></td>
                                        <td>
                                            <?php if ($user_item->role == 'admin'): ?>
                                                <span class="badge bg-danger">Admin</span>
                                            <?php elseif ($user_item->role == 'teacher'): ?>
                                                <span class="badge bg-info">Teacher</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Student</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($user_item->created_at)) ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('admin/edit_user/' . $user_item->id) ?>"
                                                    class="btn btn-sm btn-outline-primary" title="Edit User">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <?php if ($user_item->id != $this->session->userdata('user_id')): ?>
                                                    <?= form_open('admin/delete_user/' . $user_item->id, ['style' => 'display:inline;', 'onsubmit' => "return confirm('Are you sure you want to delete this user?');"]) ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                    <?= form_close() ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noResults">
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

<script>
    $(function () {
        const $searchInput = $('#searchInput');
        const $roleFilter = $('#roleFilter');
        const $sortBy = $('#sortBy');
        const $tableBody = $('#usersTableBody');
        const $resultCount = $('#resultCount');
        const $form = $('#userFilterForm');

        // Cache original rows for client-side filtering
        let allRows = $tableBody.find('.user-row').toArray();

        function applyClientFilter() {
            const searchTerm = $searchInput.val().toLowerCase().trim();
            const selectedRole = $roleFilter.val();
            const sortField = $sortBy.val();

            let visibleRows = allRows.filter(row => {
                const $row = $(row);
                const name = $row.data('name');
                const email = $row.data('email');
                const id = String($row.data('id'));
                const role = $row.data('role');

                const matchesSearch = !searchTerm ||
                    (name && name.indexOf(searchTerm) !== -1) ||
                    (email && email.indexOf(searchTerm) !== -1) ||
                    (id && id.indexOf(searchTerm) !== -1);

                const matchesRole = !selectedRole || role === selectedRole;

                return matchesSearch && matchesRole;
            });

            visibleRows.sort((a, b) => {
                const $a = $(a);
                const $b = $(b);
                let aVal, bVal;

                switch (sortField) {
                    case 'name':
                        aVal = $a.data('name');
                        bVal = $b.data('name');
                        return aVal.localeCompare(bVal);
                    case 'email':
                        aVal = $a.data('email');
                        bVal = $b.data('email');
                        return aVal.localeCompare(bVal);
                    case 'role':
                        aVal = $a.data('role');
                        bVal = $b.data('role');
                        return aVal.localeCompare(bVal);
                    case 'date':
                    default:
                        aVal = parseInt($a.data('date'), 10) || 0;
                        bVal = parseInt($b.data('date'), 10) || 0;
                        return bVal - aVal; // Newest first
                }
            });

            $tableBody.empty();

            if (visibleRows.length > 0) {
                visibleRows.forEach(row => $tableBody.append(row));
            } else {
                $tableBody.html('<tr><td colspan="6" class="text-center py-4"><i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i><p class="mt-2 text-muted">No users found matching your criteria</p></td></tr>');
            }

            $resultCount.text(visibleRows.length);
        }

        // Client-side live filters
        $searchInput.on('keyup', function () {
            applyClientFilter();
        });

        $roleFilter.on('change', function () {
            applyClientFilter();
        });

        $sortBy.on('change', function () {
            applyClientFilter();
        });

        // Submit to server when the form is submitted (for server-side filtering)
        $form.on('submit', function () {
            // allow normal GET submit – server will pre-filter results
        });

        // Initial filter on page load (client-side refinement of server results)
        applyClientFilter();
    });
</script>

<?php $this->load->view('templates/footer'); ?>
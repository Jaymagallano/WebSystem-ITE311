<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'LMS Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-theme.css') ?>" rel="stylesheet">
    <script>
        window.base_url = '<?= base_url() ?>';
        window.csrf_token_name = '<?= $this->security->get_csrf_token_name() ?>';
        window.csrf_hash = '<?= $this->security->get_csrf_hash() ?>';
    </script>
    <style>
        /* Custom Helper for specific layout tweaks if needed, but relying mostly on admin-theme.css now */
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>


    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <!-- User Profile Section at Top -->
                <div class="sidebar-brand" style="padding: 24px 20px;">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center shadow-lg"
                            style="width: 60px; height: 60px; font-size: 1.8rem; background: rgba(255,255,255,0.1); border-radius: 50%; color: #fff; backdrop-filter: blur(5px);">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    </div>
                    <h6 class="text-white text-center mb-2 fw-bold" style="letter-spacing: 0.5px;">
                        <?= $this->session->userdata('name') ?>
                    </h6>

                    <!-- Role Badge with Dropdown -->
                    <div class="text-center">
                        <div class="dropdown">
                            <button class="badge dropdown-toggle" type="button" id="roleBadgeDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="background: rgba(255,255,255,0.2); border: none; cursor: pointer; font-size: 0.85rem; padding: 6px 12px;">
                                <?= ucfirst($this->session->userdata('role')) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="roleBadgeDropdown"
                                style="min-width: 150px;">
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <nav class="nav flex-column px-3">
                    <hr class="text-white my-2">

                    <!-- Common Menu Items -->
                    <a class="nav-link <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>"
                        href="<?= base_url('dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>

                    <?php if ($this->session->userdata('role') == 'admin'): ?>
                        <!-- Admin Menu Items -->
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['users', 'create_user', 'edit_user'])) ? 'active' : '' ?>"
                            href="<?= base_url('admin/users') ?>">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'courses' ? 'active' : '' ?>"
                            href="<?= base_url('admin/courses') ?>">
                            <i class="bi bi-book"></i> Manage Courses
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'settings' ? 'active' : '' ?>"
                            href="<?= base_url('admin/settings') ?>">
                            <i class="bi bi-gear"></i> System Settings
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'reports' ? 'active' : '' ?>"
                            href="<?= base_url('admin/reports') ?>">
                            <i class="bi bi-bar-chart"></i> Reports
                        </a>
                    <?php elseif ($this->session->userdata('role') == 'teacher'): ?>
                        <!-- Teacher Menu Items -->
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['courses', 'create_course', 'edit_course'])) ? 'active' : '' ?>"
                            href="<?= base_url('teacher/courses') ?>">
                            <i class="bi bi-journal-text"></i> My Courses
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'students' ? 'active' : '' ?>"
                            href="<?= base_url('teacher/students') ?>">
                            <i class="bi bi-people"></i> Students
                        </a>
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['assignments', 'create_assignment', 'assignment_submissions', 'assignment_stats'])) ? 'active' : '' ?>"
                            href="<?= base_url('teacher/assignments') ?>">
                            <i class="bi bi-clipboard-check"></i> Assignments
                        </a>
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['grades', 'student_grades', 'grade_submission'])) ? 'active' : '' ?>"
                            href="<?= base_url('teacher/grades') ?>">
                            <i class="bi bi-calculator"></i> Grades
                        </a>
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['materials', 'upload_material'])) ? 'active' : '' ?>"
                            href="<?= base_url('teacher/materials') ?>">
                            <i class="bi bi-file-earmark-text"></i> Materials
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'reports' ? 'active' : '' ?>"
                            href="<?= base_url('teacher/reports') ?>">
                            <i class="bi bi-graph-up"></i> Reports
                        </a>
                    <?php elseif ($this->session->userdata('role') == 'student'): ?>
                        <!-- Student Menu Items -->
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['courses', 'enroll', 'course_details'])) ? 'active' : '' ?>"
                            href="<?= base_url('student/courses') ?>">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['assignments', 'submit_assignment'])) ? 'active' : '' ?>"
                            href="<?= base_url('student/assignments') ?>">
                            <i class="bi bi-clipboard-check"></i> Assignments
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'grades' ? 'active' : '' ?>"
                            href="<?= base_url('student/grades') ?>">
                            <i class="bi bi-bar-chart"></i> Grades
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'schedule' ? 'active' : '' ?>"
                            href="<?= base_url('student/schedule') ?>">
                            <i class="bi bi-calendar3"></i> Schedule
                        </a>
                        <a class="nav-link <?= (in_array($this->uri->segment(2), ['resources', 'download_material'])) ? 'active' : '' ?>"
                            href="<?= base_url('student/resources') ?>">
                            <i class="bi bi-download"></i> Resources
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-0 main-content">
                <!-- Top Navigation Bar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">
                            <?= isset($page_title) ? $page_title : 'Dashboard' ?>
                        </span>
                        <div class="d-flex align-items-center ms-auto">
                            <!-- Notification Bell -->
                            <div class="dropdown me-4" id="notification-container">
                                <a href="javascript:void(0)"
                                    class="notification-bell text-decoration-none dropdown-toggle hide-arrow"
                                    id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell-fill" style="font-size: 1.25rem;"></i>
                                    <span class="notification-badge" id="notification-count"
                                        style="display: none;">0</span>
                                </a>

                                <!-- Notification Dropdown -->
                                <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0 border-0"
                                    aria-labelledby="notificationDropdown"
                                    style="width: 340px; border-radius: 12px; overflow: hidden;">
                                    <div
                                        class="notification-header bg-gradient-primary text-white p-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold text-white"><i
                                                class="bi bi-bell-fill me-2"></i>Notifications</h6>
                                        <a href="javascript:void(0)" id="mark-all-read"
                                            class="text-white small text-decoration-none opacity-75 hover-opacity-100">
                                            Mark all read
                                        </a>
                                    </div>
                                    <div class="notification-list" id="notification-list"
                                        style="max-height: 320px; overflow-y: auto;">
                                        <!-- Notifications will be loaded here via AJAX -->
                                        <div class="empty-notifications p-5 text-center text-muted">
                                            <div class="mb-3">
                                                <i class="bi bi-bell-slash fs-1 opacity-25"></i>
                                            </div>
                                            <p class="mb-0 small fw-medium">No new notifications</p>
                                        </div>
                                    </div>
                                    <div class="notification-footer p-0 border-top">
                                        <a href="#" id="view-all-notifications"
                                            class="d-block text-center py-3 text-decoration-none small fw-bold text-primary bg-light hover-bg-gray transition-colors">
                                            View All Notifications <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="d-flex align-items-center">
                                <span class="me-3 fw-medium d-none d-md-block">
                                    <?= $this->session->userdata('name') ?>
                                </span>
                                <span class="badge rounded-pill bg-primary">
                                    <?= ucfirst($this->session->userdata('role')) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'LMS Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            border-left: 4px solid;
        }
        .stat-card.admin {
            border-left-color: #dc3545;
        }
        .stat-card.teacher {
            border-left-color: #0dcaf0;
        }
        .stat-card.student {
            border-left-color: #198754;
        }
        .stat-card.users {
            border-left-color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="text-white text-center py-4">
                    <h4><i class="bi bi-mortarboard-fill"></i> LMS</h4>
                    <small><?= ucfirst($this->session->userdata('role')) ?> Panel</small>
                </div>
                
                <nav class="nav flex-column px-3">
                    <!-- Common Menu Items -->
                    <a class="nav-link <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    
                                        <?php if($this->session->userdata('role') == 'admin'): ?>
                        <!-- Admin Menu Items -->
                        <a class="nav-link <?= $this->uri->segment(2) == 'users' ? 'active' : '' ?>" href="<?= base_url('admin/users') ?>">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'courses' ? 'active' : '' ?>" href="<?= base_url('admin/courses') ?>">
                            <i class="bi bi-book"></i> Manage Courses
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'settings' ? 'active' : '' ?>" href="<?= base_url('admin/settings') ?>">
                            <i class="bi bi-gear"></i> System Settings
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'reports' ? 'active' : '' ?>" href="<?= base_url('admin/reports') ?>">
                            <i class="bi bi-bar-chart"></i> Reports
                        </a>
                                        <?php elseif($this->session->userdata('role') == 'teacher'): ?>
                        <!-- Teacher Menu Items -->
                        <a class="nav-link <?= $this->uri->segment(2) == 'courses' ? 'active' : '' ?>" href="<?= base_url('teacher/courses') ?>">
                            <i class="bi bi-journal-text"></i> My Courses
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'students' ? 'active' : '' ?>" href="<?= base_url('teacher/students') ?>">
                            <i class="bi bi-people"></i> Students
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'assignments' ? 'active' : '' ?>" href="<?= base_url('teacher/assignments') ?>">
                            <i class="bi bi-clipboard-check"></i> Assignments
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'grades' ? 'active' : '' ?>" href="<?= base_url('teacher/grades') ?>">
                            <i class="bi bi-calculator"></i> Grades
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'materials' ? 'active' : '' ?>" href="<?= base_url('teacher/materials') ?>">
                            <i class="bi bi-file-earmark-text"></i> Materials
                        </a>
                                        <?php elseif($this->session->userdata('role') == 'student'): ?>
                        <!-- Student Menu Items -->
                        <a class="nav-link <?= $this->uri->segment(2) == 'courses' ? 'active' : '' ?>" href="<?= base_url('student/courses') ?>">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'assignments' ? 'active' : '' ?>" href="<?= base_url('student/assignments') ?>">
                            <i class="bi bi-clipboard-check"></i> Assignments
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'grades' ? 'active' : '' ?>" href="<?= base_url('student/grades') ?>">
                            <i class="bi bi-bar-chart"></i> Grades
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'schedule' ? 'active' : '' ?>" href="<?= base_url('student/schedule') ?>">
                            <i class="bi bi-calendar3"></i> Schedule
                        </a>
                        <a class="nav-link <?= $this->uri->segment(2) == 'resources' ? 'active' : '' ?>" href="<?= base_url('student/resources') ?>">
                            <i class="bi bi-download"></i> Resources
                        </a>
                    <?php endif; ?>
                    
                    <!-- Common Menu Items -->
                    <hr class="text-white">
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                    <a class="nav-link" href="<?= base_url('logout') ?>">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-0">
                <!-- Top Navigation Bar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">
                            <?= isset($page_title) ? $page_title : 'Dashboard' ?>
                        </span>
                        <div class="d-flex align-items-center">
                            <span class="me-3">
                                <i class="bi bi-person-circle"></i> 
                                <?= $this->session->userdata('name') ?>
                            </span>
                            <span class="badge bg-primary">
                                <?= ucfirst($this->session->userdata('role')) ?>
                            </span>
                        </div>
                    </div>
                </nav>
                
                <!-- Page Content -->
                <div class="container-fluid p-4">

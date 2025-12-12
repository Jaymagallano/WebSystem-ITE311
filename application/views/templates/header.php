<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'LMS Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-theme.css') ?>" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5282;
            --secondary-color: #2a4365;
            --accent-color: #3182ce;
            --success-color: #2f855a;
            --warning-color: #dd6b20;
            --danger-color: #c53030;
            --info-color: #2c7a7b;
            --light-bg: #f7fafc;
            --dark-text: #2d3748;
            --border-color: #cbd5e0;
            --primary-gradient: linear-gradient(135deg, #2c5282 0%, #2a4365 100%);
            --secondary-gradient: linear-gradient(135deg, #c53030 0%, #9b2c2c 100%);
            --success-gradient: linear-gradient(135deg, #2f855a 0%, #276749 100%);
            --warning-gradient: linear-gradient(135deg, #dd6b20 0%, #c05621 100%);
            --info-gradient: linear-gradient(135deg, #2c7a7b 0%, #285e61 100%);
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #f7fafc;
            min-height: 100vh;
            color: var(--dark-text);
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 16.666667%; /* col-md-2 equivalent */
            min-height: 100vh;
            background: linear-gradient(180deg, #2c5282 0%, #2a4365 100%);
            border-right: 1px solid var(--border-color);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 1000;
        }
        

        
        .sidebar .nav {
            position: relative;
            z-index: 1;
        }
        
        .sidebar-brand {
            background: rgba(0, 0, 0, 0.15);
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding: 25px 20px;
            text-align: center;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 14px 20px;
            margin: 3px 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 400;
            font-size: 15px;
            border-left: 3px solid transparent;
        }
        

        
        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #63b3ed;
            transform: translateX(3px);
        }
        
        .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            border-left-color: #63b3ed;
            font-weight: 500;
        }
        
        .nav-link i {
            margin-right: 10px;
            font-size: 1em;
            width: 20px;
            text-align: center;
        }
        
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        

        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        
        .stat-card {
            border-left: 4px solid var(--accent-color);
        }
        
        .stat-card.admin {
            border-left-color: #c53030;
        }
        
        .stat-card.teacher {
            border-left-color: #2c7a7b;
        }
        
        .stat-card.student {
            border-left-color: #2f855a;
        }
        
        .stat-card.users {
            border-left-color: #3182ce;
        }
        
        .navbar {
            background: white !important;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            border: none;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #2f855a 0%, #276749 100%);
            border: none;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #c53030 0%, #9b2c2c 100%);
            border: none;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #2c7a7b 0%, #285e61 100%);
            border: none;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #dd6b20 0%, #c05621 100%);
            border: none;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .table thead th {
            background: #edf2f7;
            color: var(--dark-text);
            border-bottom: 2px solid #cbd5e0;
            font-weight: 600;
            padding: 14px;
            font-size: 14px;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.8em;
            letter-spacing: 0.3px;
        }
        
        .alert {
            border-radius: 10px;
            border: 1px solid transparent;
            padding: 14px 18px;
            font-size: 15px;
        }
        
        .main-content {
            margin-left: 16.666667%; /* col-md-2 equivalent */
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Scrollbar styling for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="sidebar-brand">
                    <h5 class="text-white mb-1 fw-normal">
                        <i class="bi bi-building"></i> 
                        <span>Learning Management System</span>
                    </h5>
                    <small class="text-white-50"><?= ucfirst($this->session->userdata('role')) ?> Dashboard</small>
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
            <div class="col-md-10 p-0 main-content">
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
                            <span class="badge" style="background: linear-gradient(135deg, #2c5282 0%, #2a4365 100%);">
                                <?= ucfirst($this->session->userdata('role')) ?>
                            </span>
                        </div>
                    </div>
                </nav>
                
                <!-- Page Content -->
                <div class="container-fluid p-4">

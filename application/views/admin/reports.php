<?php $this->load->view('templates/header', ['page_title' => 'System Reports']); ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-bar-chart"></i> System Reports</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card users">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Users</h6>
                        <h3 class="mb-0"><?= isset($total_users) ? $total_users : 0 ?></h3>
                    </div>
                    <div class="text-primary" style="font-size: 2rem;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card admin">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Admins</h6>
                        <h3 class="mb-0"><?= isset($total_admins) ? $total_admins : 0 ?></h3>
                    </div>
                    <div class="text-danger" style="font-size: 2rem;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card teacher">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Teachers</h6>
                        <h3 class="mb-0"><?= isset($total_teachers) ? $total_teachers : 0 ?></h3>
                    </div>
                    <div class="text-info" style="font-size: 2rem;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card student">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Students</h6>
                        <h3 class="mb-0"><?= isset($total_students) ? $total_students : 0 ?></h3>
                    </div>
                    <div class="text-success" style="font-size: 2rem;">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Total Courses</h5>
            </div>
            <div class="card-body text-center">
                <h1 class="display-4 text-primary"><?= isset($total_courses) ? $total_courses : 0 ?></h1>
                <p class="text-muted">Active Courses</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Course Enrollments</h5>
            </div>
            <div class="card-body">
                <?php if(isset($course_enrollments) && count($course_enrollments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Enrollments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($course_enrollments as $enrollment): ?>
                                    <tr>
                                        <td><?= $enrollment->title ?></td>
                                        <td><span class="badge bg-success"><?= $enrollment->enrollment_count ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No enrollment data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- User Growth Chart -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>User Growth (Last 6 Months)</h5>
                    <span class="badge bg-primary">Live Data</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Export Reports -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-download me-2"></i>Export Reports</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Download comprehensive system reports in various formats</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="<?= base_url('admin/export_excel') ?>" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-file-earmark-excel me-2"></i>Export to CSV
                            <small class="d-block mt-1" style="font-size: 0.75rem;">Download user data in CSV format</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('admin/export_pdf') ?>" class="btn btn-danger w-100 btn-lg">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Export to HTML
                            <small class="d-block mt-1" style="font-size: 0.75rem;">Download full report in HTML</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <button onclick="window.print()" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-printer me-2"></i>Print Report
                            <small class="d-block mt-1" style="font-size: 0.75rem;">Print current page</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// User Growth Chart
const ctx = document.getElementById('userGrowthChart').getContext('2d');
const userGrowthChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($user_growth, 'month')) ?>,
        datasets: [{
            label: 'Total Users Registered',
            data: <?= json_encode(array_column($user_growth, 'count')) ?>,
            backgroundColor: 'rgba(44, 82, 130, 0.1)',
            borderColor: 'rgba(44, 82, 130, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#2c5282',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                        weight: '500'
                    },
                    padding: 20
                }
            },
            tooltip: {
                backgroundColor: 'rgba(44, 82, 130, 0.9)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return ' ' + context.parsed.y + ' users registered';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 12
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>

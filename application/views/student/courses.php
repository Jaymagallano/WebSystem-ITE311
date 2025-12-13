<?php $this->load->view('templates/header', ['page_title' => 'My Courses']); ?>

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
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-1 fw-normal">
                            <i class="bi bi-book me-2"></i>My Courses
                        </h3>
                        <p class="mb-0 small">View enrolled and available courses</p>
                    </div>
                    <div>
                        <i class="bi bi-mortarboard-fill" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enrolled Courses -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="section-icon me-3" style="width: 50px; height: 50px; background: var(--success-gradient); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white;">
                <i class="bi bi-bookmark-check-fill"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">My Enrolled Courses</h4>
                <p class="text-muted mb-0">Continue your learning journey</p>
            </div>
        </div>
    </div>
    
    <?php if(isset($enrolled_courses) && count($enrolled_courses) > 0): ?>
        <?php foreach($enrolled_courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 enrolled-course" style="border: 2px solid transparent; background: linear-gradient(white, white) padding-box, var(--success-gradient) border-box;">
                    <div class="card-header border-0" style="background: var(--success-gradient); color: white; border-radius: 18px 18px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 fw-bold"><?= $course->title ?></h5>
                                <span class="badge bg-light text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>Enrolled
                                </span>
                            </div>
                            <div>
                                <i class="bi bi-bookmark-check-fill" style="font-size: 1.5rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="course-info mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-code-square text-primary me-2"></i>
                                <span class="fw-medium"><?= $course->code ?></span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-badge text-info me-2"></i>
                                <span class="text-muted"><?= $course->teacher_name ?></span>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3"><?= substr($course->description, 0, 100) ?>...</p>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" style="width: 65%; background: var(--success-gradient);"></div>
                        </div>
                        <small class="text-muted"><i class="bi bi-graph-up me-1"></i>65% Complete</small>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <a href="<?= base_url('student/course_details/'.$course->id) ?>" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-play-circle-fill me-2"></i>Continue Learning
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, rgba(67, 172, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%);">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="bi bi-bookmark-plus pulse" style="font-size: 4rem; background: var(--success-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Start Your Learning Adventure! 📚</h4>
                    <p class="text-muted mb-4">You haven't enrolled in any courses yet. Browse available courses below to begin your journey.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-success btn-lg px-4 floating" onclick="document.getElementById('available-courses').scrollIntoView({behavior: 'smooth'})">
                            <i class="bi bi-search me-2"></i>Browse Courses
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Available Courses -->
<div class="row" id="available-courses">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="section-icon me-3" style="width: 50px; height: 50px; background: var(--primary-gradient); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white;">
                <i class="bi bi-collection-fill"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Available Courses</h4>
                <p class="text-muted mb-0">Discover new learning opportunities</p>
            </div>
        </div>
    </div>
    
    <?php if(isset($available_courses) && count($available_courses) > 0): ?>
        <?php foreach($available_courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 available-course">
                    <div class="card-header border-0" style="background: var(--primary-gradient); color: white; border-radius: 20px 20px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 fw-bold"><?= $course->title ?></h5>
                                <span class="badge bg-light text-primary">
                                    <i class="bi bi-star-fill me-1"></i>Available
                                </span>
                            </div>
                            <div>
                                <i class="bi bi-plus-circle-fill" style="font-size: 1.5rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="course-info mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-code-square text-primary me-2"></i>
                                <span class="fw-medium"><?= $course->code ?></span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-badge text-info me-2"></i>
                                <span class="text-muted"><?= $course->teacher_name ?></span>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3"><?= substr($course->description, 0, 100) ?>...</p>
                        <div class="course-features">
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="bi bi-clock text-warning"></i>
                                    <small class="d-block text-muted">Self-paced</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-award text-success"></i>
                                    <small class="d-block text-muted">Certificate</small>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-people text-info"></i>
                                    <small class="d-block text-muted">Community</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <a href="<?= base_url('student/enroll/'.$course->id) ?>" class="btn btn-primary w-100 btn-lg enroll-btn" data-action="enroll">
                            <i class="bi bi-plus-circle-fill me-2"></i>Enroll Now
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="bi bi-collection pulse" style="font-size: 4rem; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">All Caught Up! 🎉</h4>
                    <p class="text-muted mb-4">You've enrolled in all available courses or new courses will be added soon.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-outline-primary btn-lg px-4 floating" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Check for Updates
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $this->load->view('templates/footer'); ?>

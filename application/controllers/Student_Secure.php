<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Secure Student Controller
 * 
 * This is an improved version of the Student controller with enhanced security:
 * - CSRF protection
 * - Input validation
 * - SQL injection prevention
 * - Authorization checks
 * - Data tampering prevention
 */
class Student_Secure extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Load required libraries and helpers
        $this->load->library('form_validation');
        $this->load->helper('security');
        
        // Check if user is logged in and is a student
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page.');
            redirect('login');
        }
        
        if ($this->session->userdata('role') !== 'student') {
            $this->session->set_flashdata('error', 'Access denied. Students only.');
            redirect('dashboard');
        }
        
        $this->load->model('Course_model');
        $this->load->model('Assignment_model');
        $this->load->model('Grade_model');
        $this->load->model('Material_model');
    }
    
    public function courses() {
        $data['user'] = $this->session->userdata();
        $data['enrolled_courses'] = $this->Course_model->get_student_courses($this->session->userdata('user_id'));
        $data['available_courses'] = $this->Course_model->get_available_courses($this->session->userdata('user_id'));
        
        // Generate CSRF token for enrollment forms
        $data['csrf_token'] = $this->security->get_csrf_hash();
        
        $this->load->view('student/courses_secure', $data);
    }
    
    /**
     * Secure enrollment method with comprehensive validation
     */
    public function enroll($course_id = null) {
        // Validate course_id parameter
        if (!$this->_validate_course_id($course_id)) {
            $this->session->set_flashdata('error', 'Invalid course ID.');
            redirect('student/courses');
        }
        
        // Convert to integer to prevent SQL injection
        $course_id = (int) $course_id;
        
        // Check if course exists
        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('student/courses');
        }
        
        // Get student ID from session (never trust client input)
        $student_id = (int) $this->session->userdata('user_id');
        
        // Check if already enrolled
        if ($this->Course_model->is_enrolled($student_id, $course_id)) {
            $this->session->set_flashdata('error', 'You are already enrolled in this course.');
            redirect('student/courses');
        }
        
        // Prepare enrollment data using session data only
        $enrollment_data = array(
            'student_id' => $student_id,
            'course_id' => $course_id,
            'enrolled_at' => date('Y-m-d H:i:s')
        );
        
        // Attempt enrollment
        if ($this->Course_model->enroll_student($enrollment_data)) {
            $this->session->set_flashdata('success', 'Successfully enrolled in course: ' . htmlspecialchars($course->title));
            
            // Log the enrollment for audit trail
            $this->_log_enrollment($student_id, $course_id, 'SUCCESS');
        } else {
            $this->session->set_flashdata('error', 'Failed to enroll in course. Please try again.');
            $this->_log_enrollment($student_id, $course_id, 'FAILED');
        }
        
        redirect('student/courses');
    }
    
    /**
     * AJAX endpoint for secure enrollment with CSRF protection
     */
    public function enroll_ajax() {
        // Check if request is AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        // Set JSON response header
        $this->output->set_content_type('application/json');
        
        // Validate CSRF token
        if (!$this->security->verify_csrf_token()) {
            $response = array('success' => false, 'message' => 'Invalid security token.');
            $this->output->set_output(json_encode($response));
            return;
        }
        
        // Get and validate course_id
        $course_id = $this->input->post('course_id', TRUE);
        if (!$this->_validate_course_id($course_id)) {
            $response = array('success' => false, 'message' => 'Invalid course ID.');
            $this->output->set_output(json_encode($response));
            return;
        }
        
        $course_id = (int) $course_id;
        $student_id = (int) $this->session->userdata('user_id');
        
        // Check if course exists
        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course) {
            $response = array('success' => false, 'message' => 'Course not found.');
            $this->output->set_output(json_encode($response));
            return;
        }
        
        // Check if already enrolled
        if ($this->Course_model->is_enrolled($student_id, $course_id)) {
            $response = array('success' => false, 'message' => 'Already enrolled in this course.');
            $this->output->set_output(json_encode($response));
            return;
        }
        
        // Prepare enrollment data
        $enrollment_data = array(
            'student_id' => $student_id,
            'course_id' => $course_id,
            'enrolled_at' => date('Y-m-d H:i:s')
        );
        
        // Attempt enrollment
        if ($this->Course_model->enroll_student($enrollment_data)) {
            $response = array(
                'success' => true, 
                'message' => 'Successfully enrolled in ' . htmlspecialchars($course->title),
                'csrf_token' => $this->security->get_csrf_hash()
            );
            $this->_log_enrollment($student_id, $course_id, 'SUCCESS');
        } else {
            $response = array('success' => false, 'message' => 'Enrollment failed. Please try again.');
            $this->_log_enrollment($student_id, $course_id, 'FAILED');
        }
        
        $this->output->set_output(json_encode($response));
    }
    
    public function course_details($course_id = null) {
        // Validate course_id
        if (!$this->_validate_course_id($course_id)) {
            $this->session->set_flashdata('error', 'Invalid course ID.');
            redirect('student/courses');
        }
        
        $course_id = (int) $course_id;
        $student_id = (int) $this->session->userdata('user_id');
        
        // Check if course exists and student is enrolled
        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course || !$this->Course_model->is_enrolled($student_id, $course_id)) {
            $this->session->set_flashdata('error', 'Course not found or you are not enrolled.');
            redirect('student/courses');
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        $data['assignments'] = $this->Assignment_model->get_course_assignments($course_id);
        $data['materials'] = $this->Material_model->get_course_materials($course_id);
        
        $this->load->view('student/course_details', $data);
    }
    
    /**
     * Validate course ID parameter
     */
    private function _validate_course_id($course_id) {
        // Check if course_id is provided
        if (empty($course_id)) {
            return false;
        }
        
        // Check if it's numeric
        if (!is_numeric($course_id)) {
            return false;
        }
        
        // Check if it's a positive integer
        $course_id = (int) $course_id;
        if ($course_id <= 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Log enrollment attempts for audit trail
     */
    private function _log_enrollment($student_id, $course_id, $status) {
        $log_data = array(
            'student_id' => $student_id,
            'course_id' => $course_id,
            'status' => $status,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        // Log to file or database
        log_message('info', 'Enrollment attempt: ' . json_encode($log_data));
    }
    
    /**
     * Rate limiting for enrollment attempts
     */
    private function _check_rate_limit() {
        $student_id = $this->session->userdata('user_id');
        $cache_key = 'enrollment_attempts_' . $student_id;
        
        $attempts = $this->cache->get($cache_key);
        if ($attempts === FALSE) {
            $attempts = 0;
        }
        
        if ($attempts >= 5) { // Max 5 attempts per minute
            return false;
        }
        
        $this->cache->save($cache_key, $attempts + 1, 60); // Cache for 1 minute
        return true;
    }
}
?>
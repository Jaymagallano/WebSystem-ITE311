<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get student by ID with detailed information
    public function get_student_by_id($student_id) {
        $this->db->select('users.*, COUNT(DISTINCT enrollments.id) as enrolled_courses');
        $this->db->from('users');
        $this->db->join('enrollments', 'users.id = enrollments.student_id', 'left');
        $this->db->where('users.id', $student_id);
        $this->db->where('users.role', 'student');
        $this->db->group_by('users.id');
        return $this->db->get()->row();
    }
    
    // Update student information
    public function update_student($student_id, $data) {
        $this->db->where('id', $student_id);
        $this->db->where('role', 'student');
        return $this->db->update('users', $data);
    }
    
    // Get student's enrolled courses
    public function get_student_courses($student_id) {
        $this->db->select('courses.*, enrollments.enrolled_at, users.name as teacher_name');
        $this->db->from('courses');
        $this->db->join('enrollments', 'courses.id = enrollments.course_id');
        $this->db->join('users', 'courses.teacher_id = users.id');
        $this->db->where('enrollments.student_id', $student_id);
        $this->db->order_by('enrollments.enrolled_at', 'DESC');
        return $this->db->get()->result();
    }
    
    // Get student's assignment submissions
    public function get_student_submissions($student_id, $limit = null) {
        $this->db->select('assignment_submissions.*, assignments.title, assignments.max_points, courses.title as course_title');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->where('assignment_submissions.student_id', $student_id);
        $this->db->order_by('assignment_submissions.submitted_at', 'DESC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result();
    }
    
    // Archive/deactivate student
    public function archive_student($student_id) {
        return $this->db->update('users', ['status' => 'inactive'], ['id' => $student_id, 'role' => 'student']);
    }
    
    // Activate student
    public function activate_student($student_id) {
        return $this->db->update('users', ['status' => 'active'], ['id' => $student_id, 'role' => 'student']);
    }
    
    // Enroll student in course
    public function enroll_student($student_id, $course_id) {
        // Check if already enrolled
        $this->db->where('student_id', $student_id);
        $this->db->where('course_id', $course_id);
        $existing = $this->db->get('enrollments')->row();
        
        if ($existing) {
            return false; // Already enrolled
        }
        
        $data = [
            'student_id' => $student_id,
            'course_id' => $course_id,
            'enrolled_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('enrollments', $data);
    }
    
    // Unenroll student from course
    public function unenroll_student($student_id, $course_id) {
        $this->db->where('student_id', $student_id);
        $this->db->where('course_id', $course_id);
        return $this->db->delete('enrollments');
    }
    
    // Get all students with filters
    public function get_all_students($filters = []) {
        $this->db->select('users.*, COUNT(DISTINCT enrollments.id) as enrolled_courses');
        $this->db->from('users');
        $this->db->join('enrollments', 'users.id = enrollments.student_id', 'left');
        $this->db->where('users.role', 'student');
        
        // Apply filters
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('users.name', $filters['search']);
            $this->db->or_like('users.email', $filters['search']);
            $this->db->or_like('users.id', $filters['search']);
            $this->db->group_end();
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('users.status', $filters['status']);
        }
        
        $this->db->group_by('users.id');
        
        // Apply sorting
        if (!empty($filters['sort_by'])) {
            $order = !empty($filters['sort_order']) ? $filters['sort_order'] : 'asc';
            $this->db->order_by('users.' . $filters['sort_by'], $order);
        } else {
            $this->db->order_by('users.name', 'asc');
        }
        
        return $this->db->get()->result();
    }
    
    // Bulk import students
    public function bulk_insert_students($students_data) {
        return $this->db->insert_batch('users', $students_data);
    }
    
    // Get student statistics
    public function get_student_stats($student_id) {
        $stats = [];
        
        // Total enrolled courses
        $this->db->where('student_id', $student_id);
        $stats['total_courses'] = $this->db->count_all_results('enrollments');
        
        // Total submissions
        $this->db->where('student_id', $student_id);
        $stats['total_submissions'] = $this->db->count_all_results('assignment_submissions');
        
        // Graded submissions
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'graded');
        $stats['graded_submissions'] = $this->db->count_all_results('assignment_submissions');
        
        // Average grade
        $this->db->select_avg('points_earned');
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'graded');
        $result = $this->db->get('assignment_submissions')->row();
        $stats['average_grade'] = $result->points_earned ?? 0;
        
        return $stats;
    }
}

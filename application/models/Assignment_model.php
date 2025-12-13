<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment_model extends CI_Model {
    
    public function get_teacher_assignments($teacher_id) {
        $this->db->select('assignments.*, courses.title as course_title, courses.code as course_code');
        $this->db->from('assignments');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->where('courses.teacher_id', $teacher_id);
        $this->db->order_by('assignments.due_date', 'DESC');
        return $this->db->get()->result();
    }
    
        public function get_student_assignments($student_id) {
        $this->db->select('assignments.*, courses.title as course_title, courses.code as course_code, assignment_submissions.id as submission_id, assignment_submissions.submitted_at, assignment_submissions.score');
        $this->db->from('assignments');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->join('enrollments', "enrollments.course_id = courses.id AND enrollments.student_id = $student_id");
        $this->db->join('assignment_submissions', "assignment_submissions.assignment_id = assignments.id AND assignment_submissions.student_id = $student_id", 'left');
        $this->db->order_by('assignments.due_date', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_course_assignments($course_id) {
        $this->db->where('course_id', $course_id);
        $this->db->order_by('due_date', 'ASC');
        return $this->db->get('assignments')->result();
    }
    
    public function get_assignment_by_id($assignment_id) {
        $this->db->select('assignments.*, courses.title as course_title, courses.code as course_code');
        $this->db->from('assignments');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->where('assignments.id', $assignment_id);
        return $this->db->get()->row();
    }
    
    public function create_assignment($data) {
        return $this->db->insert('assignments', $data);
    }
    
    public function update_assignment($assignment_id, $data) {
        return $this->db->where('id', $assignment_id)->update('assignments', $data);
    }
    
        public function submit_assignment($data) {
        // Check if already submitted
        $this->db->where('assignment_id', $data['assignment_id']);
        $this->db->where('student_id', $data['student_id']);
        $existing = $this->db->get('assignment_submissions')->row();
        
        if ($existing) {
            // Update existing submission
            return $this->db->where('id', $existing->id)->update('assignment_submissions', $data);
        } else {
            // Insert new submission
            return $this->db->insert('assignment_submissions', $data);
        }
    }
    
        public function get_assignment_submissions($assignment_id) {
        $this->db->select('assignment_submissions.*, users.name as student_name, users.email as student_email, assignments.course_id');
        $this->db->from('assignment_submissions');
        $this->db->join('users', 'assignment_submissions.student_id = users.id');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->where('assignment_submissions.assignment_id', $assignment_id);
        $this->db->order_by('assignment_submissions.submitted_at', 'DESC');
        $submissions = $this->db->get()->result();
        
        // Add computed status field based on graded_at
        foreach ($submissions as $submission) {
            $submission->status = ($submission->graded_at !== null) ? 'graded' : 'pending';
            $submission->points_earned = $submission->score; // Alias for consistency
        }
        
        return $submissions;
    }
}

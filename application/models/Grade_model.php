<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade_model extends CI_Model {
    
        public function get_student_grades($student_id) {
        $this->db->select('assignment_submissions.*, assignments.title as assignment_title, assignments.max_points, courses.title as course_title, courses.code as course_code');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->where('assignment_submissions.student_id', $student_id);
        $this->db->where('assignment_submissions.score IS NOT NULL');
        $this->db->order_by('assignment_submissions.submitted_at', 'DESC');
        return $this->db->get()->result();
    }
    
        public function get_course_grades($course_id, $student_id) {
        $this->db->select('assignment_submissions.*, assignments.title as assignment_title, assignments.max_points');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->where('assignments.course_id', $course_id);
        $this->db->where('assignment_submissions.student_id', $student_id);
        $this->db->where('assignment_submissions.score IS NOT NULL');
        return $this->db->get()->result();
    }
    
        public function update_grade($submission_id, $score, $feedback = null) {
        $data = array(
            'score' => $score,
            'feedback' => $feedback,
            'graded_at' => date('Y-m-d H:i:s')
        );
        
        return $this->db->where('id', $submission_id)->update('assignment_submissions', $data);
    }
}

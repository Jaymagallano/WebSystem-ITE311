<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends CI_Model
{

    public function get_all_courses()
    {
        $this->db->select('courses.*, users.name as teacher_name');
        $this->db->from('courses');
        $this->db->join('users', 'courses.teacher_id = users.id', 'left');
        $this->db->order_by('courses.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_teacher_courses($teacher_id)
    {
        $this->db->where('teacher_id', $teacher_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('courses')->result();
    }

    public function get_student_courses($student_id)
    {
        $this->db->select('courses.*, users.name as teacher_name');
        $this->db->from('enrollments');
        $this->db->join('courses', 'enrollments.course_id = courses.id');
        $this->db->join('users', 'courses.teacher_id = users.id', 'left');
        $this->db->where('enrollments.student_id', $student_id);
        $this->db->order_by('enrollments.enrolled_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_available_courses($student_id)
    {
        $this->db->select('courses.*, users.name as teacher_name');
        $this->db->from('courses');
        $this->db->join('users', 'courses.teacher_id = users.id', 'left');
        $this->db->join('enrollments', "enrollments.course_id = courses.id AND enrollments.student_id = $student_id", 'left');
        $this->db->where('enrollments.id IS NULL');
        $this->db->order_by('courses.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_course_by_id($course_id)
    {
        $this->db->select('courses.*, users.name as teacher_name');
        $this->db->from('courses');
        $this->db->join('users', 'courses.teacher_id = users.id', 'left');
        $this->db->where('courses.id', $course_id);
        return $this->db->get()->row();
    }

    public function create_course($data)
    {
        return $this->db->insert('courses', $data);
    }

    public function update_course($course_id, $data)
    {
        return $this->db->where('id', $course_id)->update('courses', $data);
    }

    public function delete_course($course_id)
    {
        $this->db->trans_start();

        // Delete related records first (check if tables exist)
        if ($this->db->table_exists('enrollments')) {
            $this->db->where('course_id', $course_id)->delete('enrollments');
        }
        if ($this->db->table_exists('assignments')) {
            $this->db->where('course_id', $course_id)->delete('assignments');
        }
        if ($this->db->table_exists('lessons')) {
            $this->db->where('course_id', $course_id)->delete('lessons');
        }
        if ($this->db->table_exists('materials')) {
            $this->db->where('course_id', $course_id)->delete('materials');
        }

        // Then delete the course
        $this->db->where('id', $course_id)->delete('courses');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_enrolled_students($course_id)
    {
        $this->db->select('users.*, enrollments.enrolled_at');
        $this->db->from('enrollments');
        $this->db->join('users', 'enrollments.student_id = users.id');
        $this->db->where('enrollments.course_id', $course_id);
        $this->db->order_by('enrollments.enrolled_at', 'DESC');
        return $this->db->get()->result();
    }

    public function enroll_student($data)
    {
        return $this->db->insert('enrollments', $data);
    }

    public function is_enrolled($student_id, $course_id)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('course_id', $course_id);
        $query = $this->db->get('enrollments');
        return $query->num_rows() > 0;
    }
    public function get_course_enrollment_stats()
    {
        $this->db->select('courses.title, COUNT(enrollments.id) as enrollment_count');
        $this->db->from('courses');
        $this->db->join('enrollments', 'courses.id = enrollments.course_id', 'left');
        $this->db->group_by('courses.id');
        return $this->db->get()->result();
    }
}

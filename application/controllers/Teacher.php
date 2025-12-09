<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is logged in and is a teacher
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page.');
            redirect('login');
        }
        
        if ($this->session->userdata('role') !== 'teacher') {
            $this->session->set_flashdata('error', 'Access denied. Teachers only.');
            redirect('dashboard');
        }
        
        $this->load->model('Course_model');
        $this->load->model('Assignment_model');
        $this->load->model('Grade_model');
        $this->load->model('Material_model');
    }
    
    public function courses() {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/courses', $data);
    }
    
    public function create_course() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Course Title', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('code', 'Course Code', 'required|trim|is_unique[courses.code]');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'code' => strtoupper($this->input->post('code')),
                    'teacher_id' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Course_model->create_course($data)) {
                    $this->session->set_flashdata('success', 'Course created successfully!');
                    redirect('teacher/courses');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create course.');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $this->load->view('teacher/create_course', $data);
    }
    
    public function edit_course($course_id) {
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Course not found or access denied.');
            redirect('teacher/courses');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Course Title', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Course_model->update_course($course_id, $data)) {
                    $this->session->set_flashdata('success', 'Course updated successfully!');
                    redirect('teacher/courses');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        $this->load->view('teacher/edit_course', $data);
    }
    
    public function students($course_id = null) {
        $data['user'] = $this->session->userdata();
        
        if ($course_id) {
            $data['course'] = $this->Course_model->get_course_by_id($course_id);
            $data['students'] = $this->Course_model->get_enrolled_students($course_id);
        } else {
            $data['students'] = $this->db->where('role', 'student')->get('users')->result();
        }
        
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/students', $data);
    }
    
    public function assignments() {
        $data['user'] = $this->session->userdata();
        $data['assignments'] = $this->Assignment_model->get_teacher_assignments($this->session->userdata('user_id'));
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/assignments', $data);
    }
    
    public function assignment_submissions($assignment_id) {
        // Get assignment details
        $assignment = $this->Assignment_model->get_assignment_by_id($assignment_id);
        
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('teacher/assignments');
        }
        
        // Verify assignment belongs to teacher's course
        $course = $this->Course_model->get_course_by_id($assignment->course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/assignments');
        }
        
        $data['user'] = $this->session->userdata();
        $data['assignment'] = $assignment;
        $data['course'] = $course;
        $data['submissions'] = $this->Assignment_model->get_assignment_submissions($assignment_id);
        
        // Get enrolled students count
        $enrolled_students = $this->Course_model->get_enrolled_students($assignment->course_id);
        $data['total_students'] = count($enrolled_students);
        $data['submitted_count'] = count($data['submissions']);
        
        $this->load->view('teacher/assignment_submissions', $data);
    }
    
    public function create_assignment() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Assignment Title', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('course_id', 'Course', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('max_points', 'Max Points', 'required|numeric');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'course_id' => $this->input->post('course_id'),
                    'due_date' => $this->input->post('due_date'),
                    'max_points' => $this->input->post('max_points'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Assignment_model->create_assignment($data)) {
                    $this->session->set_flashdata('success', 'Assignment created successfully!');
                    redirect('teacher/assignments');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/create_assignment', $data);
    }
    
    public function grades($course_id = null) {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        
        if ($course_id) {
            $data['selected_course'] = $this->Course_model->get_course_by_id($course_id);
            $data['students'] = $this->Course_model->get_enrolled_students($course_id);
            $data['assignments'] = $this->Assignment_model->get_course_assignments($course_id);
        }
        
        $this->load->view('teacher/grades', $data);
    }
    
    public function student_grades($course_id, $student_id) {
        // Verify course belongs to teacher
        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/grades');
        }
        
        // Get student info
        $student = $this->db->get_where('users', array('id' => $student_id, 'role' => 'student'))->row();
        if (!$student) {
            $this->session->set_flashdata('error', 'Student not found.');
            redirect('teacher/grades/' . $course_id);
        }
        
        // Verify student is enrolled
        if (!$this->Course_model->is_enrolled($student_id, $course_id)) {
            $this->session->set_flashdata('error', 'Student is not enrolled in this course.');
            redirect('teacher/grades/' . $course_id);
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        $data['student'] = $student;
        $data['assignments'] = $this->Assignment_model->get_course_assignments($course_id);
        
                // Get submissions for this student
        $this->db->select('assignment_submissions.*, assignments.title as assignment_title, assignments.max_points, assignments.due_date');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->where('assignment_submissions.student_id', $student_id);
        $this->db->where('assignments.course_id', $course_id);
        $this->db->order_by('assignments.due_date', 'DESC');
        $data['submissions'] = $this->db->get()->result();
        
        // Calculate average grade
        $total_score = 0;
        $total_max = 0;
        foreach ($data['submissions'] as $submission) {
            if ($submission->score !== null) {
                $total_score += $submission->score;
                $total_max += $submission->max_points;
            }
        }
        $data['average_grade'] = $total_max > 0 ? round(($total_score / $total_max) * 100, 2) : 0;
        
        $this->load->view('teacher/student_grades', $data);
    }
    
                    public function grade_submission($submission_id) {
        // Get submission details
        $this->db->select('assignment_submissions.*, assignments.course_id, assignments.id as assignment_id, assignments.max_points, courses.teacher_id');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->where('assignment_submissions.id', $submission_id);
        $submission = $this->db->get()->row();
        
        if (!$submission || $submission->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/assignments');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('score', 'Score', 'required|numeric|less_than_equal_to[' . $submission->max_points . ']');
            
            if ($this->form_validation->run()) {
                $score = $this->input->post('score');
                $feedback = $this->input->post('feedback');
                
                                if ($this->Grade_model->update_grade($submission_id, $score, $feedback)) {
                    $this->session->set_flashdata('success', 'Grade saved successfully!');
                } else {
                    $this->session->set_flashdata('error', 'Failed to save grade.');
                }
                
                // Redirect based on where the request came from
                $redirect_to = $this->input->post('redirect_to');
                if ($redirect_to === 'student_grades') {
                    $course_id = $this->input->post('course_id');
                    $student_id = $this->input->post('student_id');
                    redirect('teacher/student_grades/' . $course_id . '/' . $student_id);
                } else {
                    // Default: redirect back to assignment submissions page
                    redirect('teacher/assignment_submissions/' . $submission->assignment_id);
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
                redirect('teacher/assignment_submissions/' . $submission->assignment_id);
            }
        }
    }
    
    public function materials($course_id = null) {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        
        if ($course_id) {
            $data['selected_course'] = $this->Course_model->get_course_by_id($course_id);
            $data['materials'] = $this->Material_model->get_course_materials($course_id);
        }
        
        $this->load->view('teacher/materials', $data);
    }
    
    public function upload_material() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Title', 'required|trim');
            $this->form_validation->set_rules('course_id', 'Course', 'required');
            
            if ($this->form_validation->run()) {
                $config['upload_path'] = './uploads/materials/';
                $config['allowed_types'] = 'pdf|doc|docx|ppt|pptx|txt|jpg|jpeg|png';
                $config['max_size'] = 10240; // 10MB
                
                $this->load->library('upload', $config);
                
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }
                
                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    
                    $data = array(
                        'title' => $this->input->post('title'),
                        'description' => $this->input->post('description'),
                        'course_id' => $this->input->post('course_id'),
                        'file_name' => $upload_data['file_name'],
                        'file_path' => $upload_data['full_path'],
                        'file_type' => $upload_data['file_ext'],
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    
                    if ($this->Material_model->create_material($data)) {
                        $this->session->set_flashdata('success', 'Material uploaded successfully!');
                        redirect('teacher/materials/' . $this->input->post('course_id'));
                    }
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                }
            }
        }
        
                $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/upload_material', $data);
    }
    
    public function delete_material($material_id) {
        // Get material details
        $material = $this->Material_model->get_material_by_id($material_id);
        
        if (!$material) {
            $this->session->set_flashdata('error', 'Material not found.');
            redirect('teacher/materials');
        }
        
        // Verify material belongs to teacher's course
        $course = $this->Course_model->get_course_by_id($material->course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/materials');
        }
        
        // Delete the material
        if ($this->Material_model->delete_material($material_id)) {
            $this->session->set_flashdata('success', 'Material deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete material.');
        }
        
        redirect('teacher/materials/' . $material->course_id);
    }
}

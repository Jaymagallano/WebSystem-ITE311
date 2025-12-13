<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
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
        $this->load->model('Notification_model');
    }
    
    public function courses() {
        $data['user'] = $this->session->userdata();
        $data['enrolled_courses'] = $this->Course_model->get_student_courses($this->session->userdata('user_id'));
        $data['available_courses'] = $this->Course_model->get_available_courses($this->session->userdata('user_id'));
        $this->load->view('student/courses', $data);
    }
    
    public function enroll($course_id) {
        // CSRF Protection disabled for normal usage
        // Uncomment below lines to re-enable CSRF protection:
        /*
        $csrf_token = $this->input->post($this->config->item('csrf_token_name')) ?: $this->input->get($this->config->item('csrf_token_name'));
        $csrf_cookie = $this->input->cookie($this->config->item('csrf_cookie_name'));
        
        if (!$csrf_token || !$csrf_cookie || !hash_equals($csrf_cookie, $csrf_token)) {
            show_error('The action you have requested is not allowed (CSRF token mismatch).', 403);
        }
        */
        
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('student/courses');
        }
        
        $data = array(
            'student_id' => $this->session->userdata('user_id'),
            'course_id' => $course_id,
            'enrolled_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->Course_model->enroll_student($data)) {
            // Send enrollment notification
            $this->Notification_model->notify_enrollment(
                $this->session->userdata('user_id'),
                $course->title,
                $course_id
            );
            
            $this->session->set_flashdata('success', 'Successfully enrolled in course!');
        } else {
            $this->session->set_flashdata('error', 'Failed to enroll in course.');
        }
        
        redirect('student/courses');
    }
    
    public function course_details($course_id) {
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course || !$this->Course_model->is_enrolled($this->session->userdata('user_id'), $course_id)) {
            $this->session->set_flashdata('error', 'Course not found or you are not enrolled.');
            redirect('student/courses');
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        $data['assignments'] = $this->Assignment_model->get_course_assignments($course_id);
        $data['materials'] = $this->Material_model->get_course_materials($course_id);
        $this->load->view('student/course_details', $data);
    }
    
    public function assignments() {
        $data['user'] = $this->session->userdata();
        $data['assignments'] = $this->Assignment_model->get_student_assignments($this->session->userdata('user_id'));
        $this->load->view('student/assignments', $data);
    }
    
    public function submit_assignment($assignment_id) {
        $assignment = $this->Assignment_model->get_assignment_by_id($assignment_id);
        
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('student/assignments');
        }
        
        if ($this->input->method() == 'post') {
            $config['upload_path'] = './uploads/submissions/';
            $config['allowed_types'] = 'pdf|doc|docx|txt|zip';
            $config['max_size'] = 5120; // 5MB
            
            $this->load->library('upload', $config);
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                
                $data = array(
                    'assignment_id' => $assignment_id,
                    'student_id' => $this->session->userdata('user_id'),
                    'file_name' => $upload_data['file_name'],
                    'file_path' => $upload_data['full_path'],
                    'submitted_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Assignment_model->submit_assignment($data)) {
                    // Get course and teacher info for notification
                    $this->db->select('courses.teacher_id, users.name as student_name, assignments.title');
                    $this->db->from('assignments');
                    $this->db->join('courses', 'assignments.course_id = courses.id');
                    $this->db->join('users', 'users.id = ' . $this->session->userdata('user_id'));
                    $this->db->where('assignments.id', $assignment_id);
                    $assignment_info = $this->db->get()->row();
                    
                    // Notify teacher about submission
                    if ($assignment_info) {
                        $this->Notification_model->notify_assignment_submission(
                            $assignment_info->teacher_id,
                            $assignment_info->student_name,
                            $assignment_info->title,
                            $assignment_id
                        );
                    }
                    
                    $this->session->set_flashdata('success', 'Assignment submitted successfully!');
                    redirect('student/assignments');
                }
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['assignment'] = $assignment;
        $this->load->view('student/submit_assignment', $data);
    }
    
    public function grades() {
        $student_id = $this->session->userdata('user_id');
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_student_courses($student_id);
        $data['grades'] = $this->Grade_model->get_student_grades($student_id);
        
        // Calculate average grade per course
        $course_averages = [];
        foreach ($data['courses'] as $course) {
            $avg = $this->Grade_model->get_student_average_grade($student_id, $course->id);
            $course_averages[$course->id] = $avg;
        }
        $data['course_averages'] = $course_averages;
        
        $this->load->view('student/grades', $data);
    }
    
    public function schedule() {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_student_courses($this->session->userdata('user_id'));
        $this->load->view('student/schedule', $data);
    }
    
    public function resources($course_id = null) {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_student_courses($this->session->userdata('user_id'));
        
        // Get course from query parameter or URL segment
        $course_filter = $this->input->get('course_id') ?: $course_id;
        
        if ($course_filter) {
            if (!$this->Course_model->is_enrolled($this->session->userdata('user_id'), $course_filter)) {
                $this->session->set_flashdata('error', 'Access denied.');
                redirect('student/resources');
            }
            
            $data['selected_course'] = $this->Course_model->get_course_by_id($course_filter);
            $data['materials'] = $this->Material_model->get_course_materials($course_filter);
        }
        
        $data['course_filter'] = $course_filter;
        $this->load->view('student/resources', $data);
    }
    
    public function resources_ajax() {
        $course_id = $this->input->post('course_id');
        
        if (!$course_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Course ID is required'
                ]));
            return;
        }
        
        // Check enrollment
        if (!$this->Course_model->is_enrolled($this->session->userdata('user_id'), $course_id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Access denied. You are not enrolled in this course.'
                ]));
            return;
        }
        
        $course = $this->Course_model->get_course_by_id($course_id);
        $materials = $this->Material_model->get_course_materials($course_id);
        
        // Build HTML response
        $html = '';
        if (count($materials) > 0) {
            foreach ($materials as $material) {
                // Use relative path for download
                $download_url = base_url('uploads/materials/' . $material->file_name);
                
                $html .= '<div class="col-md-6 mb-3">';
                $html .= '<div class="card h-100">';
                $html .= '<div class="card-body">';
                $html .= '<h5 class="card-title"><i class="bi bi-file-earmark-text"></i> ' . $material->title . '</h5>';
                if ($material->description) {
                    $html .= '<p class="card-text text-muted">' . $material->description . '</p>';
                }
                $html .= '<div class="d-flex justify-content-between align-items-center">';
                $html .= '<span class="badge bg-info">' . strtoupper(pathinfo($material->file_name, PATHINFO_EXTENSION)) . '</span>';
                $html .= '<small class="text-muted">' . date('M d, Y', strtotime($material->created_at)) . '</small>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="card-footer bg-white">';
                $html .= '<a href="' . $download_url . '" class="btn btn-sm btn-primary w-100" download>';
                $html .= '<i class="bi bi-download"></i> Download';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
        } else {
            $html .= '<div class="col-12">';
            $html .= '<div class="text-center py-5">';
            $html .= '<i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>';
            $html .= '<p class="mt-2 text-muted">No materials available for this course yet.</p>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'html' => $html,
                'course_title' => $course->title,
                'course_code' => $course->code,
                'material_count' => count($materials)
            ]));
    }
    
    public function download_material($material_id) {
        $material = $this->Material_model->get_material_by_id($material_id);
        
        if (!$material || !$this->Course_model->is_enrolled($this->session->userdata('user_id'), $material->course_id)) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('student/resources');
        }
        
        $this->load->helper('download');
        force_download($material->file_path, NULL);
    }
    
    public function notifications() {
        $data['user'] = $this->session->userdata();
        $this->load->view('student/notifications', $data);
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function register() {
        $this->load->helper('security_sanitize');
        
        if ($this->input->method() == 'post') {
            // Comprehensive validation rules
            $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]|max_length[100]|regex_match[/^[a-zA-Z\s\-\.]+$/]',
                array('regex_match' => 'Name can only contain letters, spaces, hyphens, and periods'));
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[255]|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[72]|callback_check_password_strength');
            $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');
            
            if ($this->form_validation->run()) {
                // Sanitize inputs before storing
                $name = sanitize_string($this->input->post('name', TRUE));
                $email = strtolower(trim($this->input->post('email', TRUE)));
                
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'role' => 'student',
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->db->insert('users', $data)) {
                    $this->session->set_flashdata('success', 'Registration successful! Please login.');
                    redirect('login');
                }
            }
        }
        
        $this->load->view('auth/register');
    }
    
    /**
     * Callback function to check password strength
     */
    public function check_password_strength($password) {
        if (!preg_match('/[A-Za-z]/', $password)) {
            $this->form_validation->set_message('check_password_strength', 'Password must contain at least one letter');
            return FALSE;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->form_validation->set_message('check_password_strength', 'Password must contain at least one number');
            return FALSE;
        }
        return TRUE;
    }
    
    public function login() {
        // Prevent logged-in users from accessing login page
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        if ($this->input->method() == 'post') {
            // Enhanced validation with length limits
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[255]');
            $this->form_validation->set_rules('password', 'Password', 'required|max_length[72]');
            
            if ($this->form_validation->run()) {
                // Sanitize email input
                $email = strtolower(trim($this->input->post('email', TRUE)));
                $password = $this->input->post('password');
                
                // Fetch user from database
                $user = $this->db->get_where('users', array('email' => $email))->row();
                
                if ($user && password_verify($password, $user->password)) {
                    // Create session data
                    $session_data = array(
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'logged_in' => TRUE
                    );
                    
                    $this->session->set_userdata($session_data);
                    $this->session->set_flashdata('success', 'Welcome back, ' . $user->name . '!');
                    
                    // Redirect everyone to the unified dashboard
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Invalid email or password.');
                }
            }
        }
        
        $this->load->view('auth/login');
    }
    
    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('login');
    }
    
    public function dashboard() {
        // Authorization check - ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access the dashboard.');
            redirect('login');
        }
        
        // Get user data from session
        $data['user'] = $this->session->userdata();
        $role = $this->session->userdata('role');
        
        // Fetch role-specific data from database
        switch($role) {
            case 'admin':
                // Fetch all users for admin
                $data['total_users'] = $this->db->count_all('users');
                $data['total_admins'] = $this->db->where('role', 'admin')->count_all_results('users');
                $data['total_teachers'] = $this->db->where('role', 'teacher')->count_all_results('users');
                $data['total_students'] = $this->db->where('role', 'student')->count_all_results('users');
                $data['recent_users'] = $this->db->order_by('created_at', 'DESC')->limit(5)->get('users')->result();
                break;
                
            case 'teacher':
                // Load models for teacher data
                $this->load->model('Course_model');
                $this->load->model('Assignment_model');
                
                // Fetch teacher-specific data
                $teacher_id = $this->session->userdata('user_id');
                $data['total_students'] = $this->db->where('role', 'student')->count_all_results('users');
                $data['recent_students'] = $this->db->where('role', 'student')->order_by('created_at', 'DESC')->limit(5)->get('users')->result();
                
                // Get teacher's course count
                $data['total_courses'] = $this->db->where('teacher_id', $teacher_id)->count_all_results('courses');
                
                // Get teacher's assignment count
                $this->db->select('assignments.id');
                $this->db->from('assignments');
                $this->db->join('courses', 'assignments.course_id = courses.id');
                $this->db->where('courses.teacher_id', $teacher_id);
                $data['total_assignments'] = $this->db->count_all_results();
                break;
                
            case 'student':
                // Load models for student data
                $this->load->model('Course_model');
                
                // Fetch student-specific data
                $student_id = $this->session->userdata('user_id');
                $data['total_teachers'] = $this->db->where('role', 'teacher')->count_all_results('users');
                
                // Get student's enrolled course count
                $data['total_enrolled_courses'] = $this->db->where('student_id', $student_id)->count_all_results('enrollments');
                
                // Get student's pending assignments count
                $this->db->select('assignments.id');
                $this->db->from('assignments');
                $this->db->join('enrollments', 'assignments.course_id = enrollments.course_id');
                $this->db->where('enrollments.student_id', $student_id);
                $this->db->where('assignments.due_date >=', date('Y-m-d'));
                // Check if not yet submitted
                $this->db->join('assignment_submissions', "assignment_submissions.assignment_id = assignments.id AND assignment_submissions.student_id = $student_id", 'left');
                $this->db->where('assignment_submissions.id IS NULL');
                $data['total_pending_assignments'] = $this->db->count_all_results();
                break;
        }
        
        // Load the unified dashboard view
        $this->load->view('auth/dashboard', $data);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is logged in and is an admin
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page.');
            redirect('login');
        }
        
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Access denied. Admins only.');
            redirect('dashboard');
        }
        
        $this->load->model('Course_model');
    }
    
    public function users() {
        $data['user'] = $this->session->userdata();
        $data['users'] = $this->db->order_by('created_at', 'DESC')->get('users')->result();
        $this->load->view('admin/users', $data);
    }
    
    public function create_user() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,teacher,student]');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'role' => $this->input->post('role'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->db->insert('users', $data)) {
                    $this->session->set_flashdata('success', 'User created successfully!');
                    redirect('admin/users');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create user.');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $this->load->view('admin/create_user', $data);
    }
    
    public function edit_user($user_id) {
        $edit_user = $this->db->get_where('users', array('id' => $user_id))->row();
        
        if (!$edit_user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,teacher,student]');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'name' => $this->input->post('name'),
                    'role' => $this->input->post('role'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                // Update password only if provided
                if ($this->input->post('password')) {
                    $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }
                
                if ($this->db->where('id', $user_id)->update('users', $data)) {
                    $this->session->set_flashdata('success', 'User updated successfully!');
                    redirect('admin/users');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['edit_user'] = $edit_user;
        $this->load->view('admin/edit_user', $data);
    }
    
    public function delete_user($user_id) {
        // Prevent deleting own account
        if ($user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('admin/users');
        }
        
        if ($this->db->where('id', $user_id)->delete('users')) {
            $this->session->set_flashdata('success', 'User deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        
        redirect('admin/users');
    }
    
    public function courses() {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_all_courses();
        $this->load->view('admin/courses', $data);
    }
    
    public function delete_course($course_id) {
        if ($this->Course_model->delete_course($course_id)) {
            $this->session->set_flashdata('success', 'Course deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete course.');
        }
        
        redirect('admin/courses');
    }
    
    public function settings() {
        if ($this->input->method() == 'post') {
            // Save system settings here
            $this->session->set_flashdata('success', 'Settings saved successfully!');
            redirect('admin/settings');
        }
        
        $data['user'] = $this->session->userdata();
        $this->load->view('admin/settings', $data);
    }
    
    public function reports() {
        $data['user'] = $this->session->userdata();
        
        // Generate statistics
        $data['total_users'] = $this->db->count_all('users');
        $data['total_admins'] = $this->db->where('role', 'admin')->count_all_results('users');
        $data['total_teachers'] = $this->db->where('role', 'teacher')->count_all_results('users');
        $data['total_students'] = $this->db->where('role', 'student')->count_all_results('users');
        $data['total_courses'] = $this->db->count_all('courses');
        
        // Get course enrollments
        $this->db->select('courses.title, COUNT(enrollments.id) as enrollment_count');
        $this->db->from('courses');
        $this->db->join('enrollments', 'courses.id = enrollments.course_id', 'left');
        $this->db->group_by('courses.id');
        $data['course_enrollments'] = $this->db->get()->result();
        
        $this->load->view('admin/reports', $data);
    }
}

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
        
        // Get user growth data (last 6 months)
        $data['user_growth'] = $this->get_user_growth_data();
        
        $this->load->view('admin/reports', $data);
    }
    
    private function get_user_growth_data() {
        $growth_data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $month_label = date('M Y', strtotime("-$i months"));
            
            // Count users created in this month
            $this->db->like('created_at', $month, 'after');
            $count = $this->db->count_all_results('users');
            
            $growth_data[] = [
                'month' => $month_label,
                'count' => $count
            ];
        }
        
        return $growth_data;
    }
    
    public function export_excel() {
        // Load PHPSpreadsheet library (if installed) or simple CSV export
        $this->load->helper('download');
        
        // Get all users data
        $users = $this->db->order_by('created_at', 'DESC')->get('users')->result();
        
        // Create CSV content
        $csv_content = "ID,Name,Email,Role,Created At\n";
        
        foreach ($users as $user) {
            $csv_content .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $user->id,
                $user->name,
                $user->email,
                ucfirst($user->role),
                date('M d, Y', strtotime($user->created_at))
            );
        }
        
        $filename = 'lms_users_report_' . date('Y-m-d') . '.csv';
        force_download($filename, $csv_content);
    }
    
    public function export_pdf() {
        // Simple HTML to PDF approach
        $this->load->helper('download');
        
        // Get statistics
        $total_users = $this->db->count_all('users');
        $total_admins = $this->db->where('role', 'admin')->count_all_results('users');
        $total_teachers = $this->db->where('role', 'teacher')->count_all_results('users');
        $total_students = $this->db->where('role', 'student')->count_all_results('users');
        $total_courses = $this->db->count_all('courses');
        
        // Get users
        $users = $this->db->order_by('created_at', 'DESC')->get('users')->result();
        
        // Create HTML content
        $html = "<!DOCTYPE html>
<html>
<head>
    <title>LMS Report</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #2c5282; }
        .stats { display: flex; gap: 20px; margin: 20px 0; }
        .stat-box { border: 2px solid #2c5282; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c5282; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>LMS System Report</h1>
    <p>Generated: " . date('M d, Y H:i:s') . "</p>
    
    <h2>Statistics Summary</h2>
    <div class='stats'>
        <div class='stat-box'>Total Users: <strong>$total_users</strong></div>
        <div class='stat-box'>Admins: <strong>$total_admins</strong></div>
        <div class='stat-box'>Teachers: <strong>$total_teachers</strong></div>
        <div class='stat-box'>Students: <strong>$total_students</strong></div>
        <div class='stat-box'>Courses: <strong>$total_courses</strong></div>
    </div>
    
    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>";
        
        foreach ($users as $user) {
            $html .= "<tr>
                <td>{$user->id}</td>
                <td>{$user->name}</td>
                <td>{$user->email}</td>
                <td>" . ucfirst($user->role) . "</td>
                <td>" . date('M d, Y', strtotime($user->created_at)) . "</td>
            </tr>";
        }
        
        $html .= "</tbody>
    </table>
</body>
</html>";
        
        $filename = 'lms_report_' . date('Y-m-d') . '.html';
        force_download($filename, $html);
    }
}

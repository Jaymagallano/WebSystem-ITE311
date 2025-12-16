<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
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
        $this->load->model('User_model');
        $this->load->model('Settings_model'); // Load Settings Model
        $this->load->helper('download');
        $this->load->helper('form');
        $this->load->helper('security_sanitize');
        $this->load->library('form_validation');
    }

    public function users()
    {
        $data['user'] = $this->session->userdata();
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('admin/users', $data);
    }

    public function create_user()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,teacher,student]');

            // Password logic: If provided, validate strict. If empty, generate.
            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$/]', array('regex_match' => 'Password must contain at least one letter and one number.'));
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            } else {
                // No validation on password if empty (we will generate it)
            }

            if ($this->form_validation->run()) {

                // SECURITY: Only Super Admin (ID 1) can create other Admins
                if ($this->input->post('role') === 'admin') {
                    if ($this->session->userdata('user_id') != 1) {
                        $this->session->set_flashdata('error', 'Security Warning: Only the Super Admin can create new Admin accounts.');
                        redirect('admin/create_user');
                    }
                }

                // 1. Password Workflow: Auto-generate if empty
                if (empty($password)) {
                    $password = bin2hex(random_bytes(4)); // 8 chars hex
                    // In production, you would send this via email.
                    // Here we will show it in flashdata
                    $generated_msg = " A random password has been generated: <strong>$password</strong> (Simulating email sent)";
                } else {
                    $generated_msg = "";
                }

                // Sanitize all inputs before storing
                $data = array(
                    'name' => sanitize_string($this->input->post('name', TRUE)),
                    'email' => strtolower(trim($this->input->post('email', TRUE))),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $this->input->post('role', TRUE),
                    'created_at' => $this->Settings_model->get_current_timestamp(), // Fix: Timezone handling
                    'created_by' => $this->session->userdata('user_id') // Fix: Audit Trail
                );

                if ($this->User_model->create_user($data)) {
                    $this->session->set_flashdata('success', 'User created successfully!' . $generated_msg);
                    redirect('admin/users');
                } else {
                    $data['error'] = 'Failed to create user in database.';
                }
            }
            // Validation failed or DB error - fall through to load view (displaying errors)
        }

        $data['user'] = $this->session->userdata();
        $this->load->view('admin/create_user', $data);
    }

    public function edit_user($user_id)
    {
        // Validate user_id is a positive integer
        $user_id = intval($user_id);
        if ($user_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid user ID.');
            redirect('admin/users');
        }
        
        $edit_user = $this->User_model->get_user_by_id($user_id);

        if (!$edit_user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,teacher,student]');

            // Check if email is being changed
            $new_email = $this->input->post('email');
            if ($new_email && $new_email !== $edit_user->email) {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            }

            if ($this->form_validation->run()) {
                // Prevent admin from changing their own role
                if (
                    $user_id == $this->session->userdata('user_id') &&
                    $this->input->post('role') !== $this->session->userdata('role')
                ) {
                    $this->session->set_flashdata('error', 'You cannot change your own role.');
                    redirect('admin/edit_user/' . $user_id);
                }

                // Prevent changing teacher role to admin
                if ($edit_user->role == 'teacher' && $this->input->post('role') == 'admin') {
                    $this->session->set_flashdata('error', 'Teacher role cannot be changed to admin.');
                    redirect('admin/edit_user/' . $user_id);
                }

                // Prevent changing student role to admin
                if ($edit_user->role == 'student' && $this->input->post('role') == 'admin') {
                    $this->session->set_flashdata('error', 'Student role cannot be changed to admin.');
                    redirect('admin/edit_user/' . $user_id);
                }

                // Sanitize inputs before storing
                $data = array(
                    'name' => sanitize_string($this->input->post('name', TRUE)),
                    'role' => $this->input->post('role', TRUE),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                // Add email to updates if it was part of the valid submission
                if ($new_email && $new_email !== $edit_user->email) {
                    $data['email'] = strtolower(trim($new_email));
                }

                // Update password only if provided (with length validation)
                $new_password = $this->input->post('password');
                if ($new_password && strlen($new_password) >= 8 && strlen($new_password) <= 72) {
                    $data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
                }

                if ($this->User_model->update_user($user_id, $data)) {
                    $this->session->set_flashdata('success', 'User updated successfully!');
                    redirect('admin/users');
                }
            }
        }

        $data['user'] = $this->session->userdata();
        $data['edit_user'] = $edit_user;
        // Check if this is self-editing
        $data['is_self_edit'] = ($user_id == $this->session->userdata('user_id'));
        // Disable admin role selection for teacher and student users (similar to admin self-edit restriction)
        $data['disable_admin_role'] = ($edit_user->role == 'teacher' || $edit_user->role == 'student');
        $this->load->view('admin/edit_user', $data);
    }

    public function delete_user($user_id)
    {
        // Enforce POST method for deletion
        if ($this->input->method() !== 'post') {
            show_error('Method Not Allowed', 405);
        }
        
        // Validate user_id is a positive integer
        $user_id = intval($user_id);
        if ($user_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid user ID.');
            redirect('admin/users');
        }

        // Prevent deleting own account
        if ($user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('admin/users');
        }

        if ($this->User_model->delete_user($user_id)) {
            $this->session->set_flashdata('success', 'User deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }

        redirect('admin/users');
    }

    public function courses()
    {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_all_courses();
        $this->load->view('admin/courses', $data);
    }

    public function delete_course($course_id)
    {
        // Enforce POST method for deletion
        if ($this->input->method() !== 'post') {
            show_error('Method Not Allowed', 405);
        }
        
        // Validate course_id is a positive integer
        $course_id = intval($course_id);
        if ($course_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid course ID.');
            redirect('admin/courses');
        }

        if ($this->Course_model->delete_course($course_id)) {
            $this->session->set_flashdata('success', 'Course deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete course.');
        }

        redirect('admin/courses');
    }

    public function settings()
    {
        if ($this->input->method() == 'post') {
            // Validate settings inputs
            $this->form_validation->set_rules('system_name', 'System Name', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('system_email', 'System Email', 'required|trim|valid_email|max_length[255]');
            $this->form_validation->set_rules('timezone', 'Timezone', 'required|trim|max_length[50]');
            $this->form_validation->set_rules('max_file_size', 'Max File Size', 'required|integer|greater_than[0]|less_than[102400]');
            $this->form_validation->set_rules('max_students_per_course', 'Max Students', 'required|integer|greater_than[0]|less_than[10000]');
            
            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/settings');
            }
            
            // Sanitized field mapping
            $settings_data = [
                'system_name' => sanitize_string($this->input->post('system_name', TRUE)),
                'system_email' => strtolower(trim($this->input->post('system_email', TRUE))),
                'timezone' => sanitize_string($this->input->post('timezone', TRUE)),
                'notify_registration' => $this->input->post('notify_registration') ? 1 : 0,
                'notify_enrollment' => $this->input->post('notify_enrollment') ? 1 : 0,
                'notify_assignment' => $this->input->post('notify_assignment') ? 1 : 0,
                'max_file_size' => intval($this->input->post('max_file_size')),
                'max_students_per_course' => intval($this->input->post('max_students_per_course')),
            ];

            foreach ($settings_data as $key => $value) {
                $this->Settings_model->save_setting($key, $value);
            }

            $this->session->set_flashdata('success', 'Settings saved successfully!');
            redirect('admin/settings');
        }

        $data['user'] = $this->session->userdata();
        $data['settings'] = $this->Settings_model->get_all_settings(); // Get settings from DB
        $this->load->view('admin/settings', $data);
    }

    public function backup_database()
    {
        $this->load->dbutil();

        $prefs = array(
            'format' => 'zip',
            'filename' => 'my_db_backup.sql'
        );

        $backup = $this->dbutil->backup($prefs);
        $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';

        $this->load->helper('download');
        force_download($db_name, $backup);
    }

    public function clear_cache()
    {
        // Simple manual cache clearing or just a placeholder for now as CI cache path varies
        $this->db->cache_delete_all();
        $this->session->set_flashdata('success', 'Database cache cleared successfully!');
        redirect('admin/settings');
    }

    public function reset_system()
    {
        // Double check session or pass logic for super admin
        if ($this->session->userdata('role') !== 'admin') {
            show_error('Access Denied', 403);
        }

        // Truncate non-essential tables
        // Assuming we keep the current admin user but wipe everything else

        $current_admin_id = $this->session->userdata('user_id');

        $this->db->trans_start();

        // 1. Delete all other users
        $this->db->where('id !=', $current_admin_id)->delete('users');

        // 2. Truncate content tables
        $tables_to_truncate = ['courses', 'enrollments', 'assignments', 'submissions', 'announcements', 'notifications'];
        foreach ($tables_to_truncate as $table) {
            if ($this->db->table_exists($table)) {
                $this->db->truncate($table);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'System reset failed.');
        } else {
            $this->session->set_flashdata('success', 'System reset successfully. All data cleared.');
        }

        redirect('admin/settings');
    }

    public function reports()
    {
        $data['user'] = $this->session->userdata();

        // Generate statistics using Model
        $data['total_users'] = $this->User_model->count_users();
        $data['total_admins'] = $this->User_model->count_users(['role' => 'admin']);
        $data['total_teachers'] = $this->User_model->count_users(['role' => 'teacher']);
        $data['total_students'] = $this->User_model->count_users(['role' => 'student']);
        $data['total_courses'] = $this->db->count_all('courses');

        // Get course enrollments
        $data['course_enrollments'] = $this->Course_model->get_course_enrollment_stats();

        // Get user growth data (last 6 months)
        $data['user_growth'] = $this->get_user_growth_data();

        $this->load->view('admin/reports', $data);
    }

    private function get_user_growth_data()
    {
        return $this->User_model->get_monthly_user_counts(6);
    }

    public function export_excel()
    {
        // Load PHPSpreadsheet library (if installed) or simple CSV export
        $this->load->helper('download');

        // Get all users data via Model
        $users = $this->User_model->get_all_users();

        // Create CSV content
        $csv_content = "ID,Name,Email,Role,Created At\n";

        foreach ($users as $user) {
            // Sanitize CSV fields to prevent injection
            $name = $user->name;
            $email = $user->email;

            // CSV Injection prevention: Prefix risky chars with '
            if (preg_match('/^[=\+\-@]/', $name)) {
                $name = "'" . $name;
            }
            if (preg_match('/^[=\+\-@]/', $email)) {
                $email = "'" . $email;
            }

            // Escape double quotes
            $name = str_replace('"', '""', $name);
            $email = str_replace('"', '""', $email);

            $csv_content .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $user->id,
                $name,
                $email,
                ucfirst($user->role),
                date('M d, Y', strtotime($user->created_at))
            );
        }

        $filename = 'lms_users_report_' . date('Y-m-d') . '.csv';
        force_download($filename, $csv_content);
    }

    public function export_html()
    {
        // Load all users data via Model
        $data['users'] = $this->User_model->get_all_users();

        // Load the view string
        $html = $this->load->view('admin/export_users_html', $data, TRUE);

        $filename = 'lms_report_' . date('Y-m-d') . '.html';
        force_download($filename, $html);
    }


    public function notifications()
    {
        $data['user'] = $this->session->userdata();
        $this->load->view('admin/notifications', $data);
    }
}
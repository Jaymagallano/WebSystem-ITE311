<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function register() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'role' => 'user',
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
    
    public function login() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run()) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $user = $this->db->get_where('users', array('email' => $email))->row();
                
                if ($user && password_verify($password, $user->password)) {
                    $session_data = array(
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'logged_in' => TRUE
                    );
                    
                    $this->session->set_userdata($session_data);
                    $this->session->set_flashdata('success', 'Welcome back, ' . $user->name . '!');
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
        redirect('login');
    }
    
    public function dashboard() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        $data['user'] = $this->session->userdata();
        $this->load->view('auth/dashboard', $data);
    }
}

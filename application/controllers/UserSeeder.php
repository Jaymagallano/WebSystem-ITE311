<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserSeeder extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function seed() {
        $users = array(
            array(
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s')
            ),
            array(
                'name' => 'Teacher User',
                'email' => 'teacher@test.com',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'teacher',
                'created_at' => date('Y-m-d H:i:s')
            ),
            array(
                'name' => 'Student User',
                'email' => 'student@test.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'created_at' => date('Y-m-d H:i:s')
            )
        );
        
        foreach ($users as $user) {
            $this->db->insert('users', $user);
        }
        
        echo "Sample users seeded successfully!";
    }
}

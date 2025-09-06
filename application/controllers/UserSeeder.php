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
                'name' => 'Test User',
                'email' => 'user@test.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s')
            )
        );
        
        foreach ($users as $user) {
            $this->db->insert('users', $user);
        }
        
        echo "Sample users seeded successfully!";
    }
}
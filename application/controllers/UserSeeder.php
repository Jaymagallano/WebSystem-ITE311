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
                'username' => 'admin',
                'email' => 'admin@lms.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'role' => 'admin'
            ),
            array(
                'username' => 'instructor1',
                'email' => 'instructor1@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'first_name' => 'John',
                'last_name' => 'Smith',
                'role' => 'instructor'
            ),
            array(
                'username' => 'student1',
                'email' => 'student1@lms.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'role' => 'student'
            ),
            array(
                'username' => 'student2',
                'email' => 'student2@lms.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'role' => 'student'
            )
        );
        
        foreach ($users as $user) {
            $this->db->insert('users', $user);
        }
        
        echo "Sample users seeded successfully!";
    }
}
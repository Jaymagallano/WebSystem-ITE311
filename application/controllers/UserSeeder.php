<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserSeeder extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function seed()
    {
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
    public function seed_students()
    {
        $students = array(
            array('name' => 'Simon', 'email' => 'simon@test.com'),
            array('name' => 'Lia', 'email' => 'lia@test.com'),
            array('name' => 'Ping', 'email' => 'ping@test.com'),
            array('name' => 'Vona', 'email' => 'vona@test.com'),
            array('name' => 'Ken', 'email' => 'ken@test.com'),
        );

        $count = 0;
        foreach ($students as $student) {
            // Check if email already exists
            $exists = $this->db->get_where('users', ['email' => $student['email']])->num_rows() > 0;

            if (!$exists) {
                $this->db->insert('users', array(
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'password' => password_hash('password', PASSWORD_DEFAULT),
                    'role' => 'student',
                    'created_at' => date('Y-m-d H:i:s')
                ));
                $count++;
            }
        }

        echo "$count students seeded successfully!";
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_student_management_fields extends CI_Migration {
    
    public function up() {
        // Add phone column
        $fields = array(
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
                'after' => 'email'
            )
        );
        $this->dbforge->add_column('users', $fields);
        
        // Add address column
        $fields = array(
            'address' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'phone'
            )
        );
        $this->dbforge->add_column('users', $fields);
        
        // Add profile_photo column
        $fields = array(
            'profile_photo' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'after' => 'address'
            )
        );
        $this->dbforge->add_column('users', $fields);
        
        // Add status column
        $fields = array(
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('active', 'inactive'),
                'default' => 'active',
                'after' => 'profile_photo'
            )
        );
        $this->dbforge->add_column('users', $fields);
        
        // Add indexes for better performance
        $this->db->query('CREATE INDEX idx_users_status ON users(status)');
        $this->db->query('CREATE INDEX idx_users_role ON users(role)');
        
        // Add indexes for enrollments table if it exists
        if ($this->db->table_exists('enrollments')) {
            $this->db->query('CREATE INDEX idx_enrollments_student ON enrollments(student_id)');
            $this->db->query('CREATE INDEX idx_enrollments_course ON enrollments(course_id)');
        }
        
        echo "Student management fields added successfully!\n";
    }
    
    public function down() {
        // Drop indexes first
        $this->db->query('DROP INDEX IF EXISTS idx_users_status ON users');
        $this->db->query('DROP INDEX IF EXISTS idx_users_role ON users');
        
        if ($this->db->table_exists('enrollments')) {
            $this->db->query('DROP INDEX IF EXISTS idx_enrollments_student ON enrollments');
            $this->db->query('DROP INDEX IF EXISTS idx_enrollments_course ON enrollments');
        }
        
        // Drop columns
        $this->dbforge->drop_column('users', 'status');
        $this->dbforge->drop_column('users', 'profile_photo');
        $this->dbforge->drop_column('users', 'address');
        $this->dbforge->drop_column('users', 'phone');
        
        echo "Student management fields removed successfully!\n";
    }
}

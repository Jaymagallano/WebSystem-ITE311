<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_assignment_submissions_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'assignment_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'student_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),
            'file_path' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'submitted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'score' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ),
            'feedback' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'graded_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('assignment_id');
        $this->dbforge->add_key('student_id');
        $this->dbforge->create_table('assignment_submissions');
    }
    
    public function down() {
        $this->dbforge->drop_table('assignment_submissions');
    }
}

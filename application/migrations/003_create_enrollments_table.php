<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_enrollments_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'student_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'course_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'enrolled_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('student_id');
        $this->dbforge->add_key('course_id');
        $this->dbforge->create_table('enrollments');
    }
    
    public function down() {
        $this->dbforge->drop_table('enrollments');
    }
}
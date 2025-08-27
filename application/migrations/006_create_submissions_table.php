<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_submissions_table extends CI_Migration {
    
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
            'quiz_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'answer' => array(
                'type' => 'ENUM',
                'constraint' => array('a', 'b', 'c', 'd')
            ),
            'is_correct' => array(
                'type' => 'BOOLEAN',
                'default' => FALSE
            ),
            'submitted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('student_id');
        $this->dbforge->add_key('quiz_id');
        $this->dbforge->create_table('submissions');
    }
    
    public function down() {
        $this->dbforge->drop_table('submissions');
    }
}
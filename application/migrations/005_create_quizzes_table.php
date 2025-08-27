<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_quizzes_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'lesson_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'question' => array(
                'type' => 'TEXT'
            ),
            'option_a' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'option_b' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'option_c' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'option_d' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'correct_answer' => array(
                'type' => 'ENUM',
                'constraint' => array('a', 'b', 'c', 'd')
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('lesson_id');
        $this->dbforge->create_table('quizzes');
    }
    
    public function down() {
        $this->dbforge->drop_table('quizzes');
    }
}
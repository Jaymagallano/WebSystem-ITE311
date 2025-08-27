<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_lessons_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'course_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'content' => array(
                'type' => 'TEXT'
            ),
            'order_number' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('course_id');
        $this->dbforge->create_table('lessons');
    }
    
    public function down() {
        $this->dbforge->drop_table('lessons');
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_courses_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'description' => array(
                'type' => 'TEXT'
            ),
            'instructor_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
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
        $this->dbforge->add_key('instructor_id');
        $this->dbforge->create_table('courses');
    }
    
    public function down() {
        $this->dbforge->drop_table('courses');
    }
}
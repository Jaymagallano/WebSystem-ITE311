<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => TRUE
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => TRUE
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'role' => array(
                'type' => 'ENUM',
                'constraint' => array('student', 'instructor', 'admin'),
                'default' => 'student'
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
        $this->dbforge->create_table('users');
    }
    
    public function down() {
        $this->dbforge->drop_table('users');
    }
}
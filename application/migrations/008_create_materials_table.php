<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_materials_table extends CI_Migration {

    public function up()
    {
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
                'unsigned' => TRUE,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'file_path' => array(
                'type' => 'TEXT',
            ),
            'file_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('materials');
    }

    public function down()
    {
        $this->dbforge->drop_table('materials');
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_assignments_table extends CI_Migration {

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
            ),
            'due_date' => array(
                'type' => 'DATETIME',
            ),
            'max_points' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 100,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('assignments');
    }

    public function down()
    {
        $this->dbforge->drop_table('assignments');
    }
}

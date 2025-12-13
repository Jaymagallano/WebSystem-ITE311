<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_notifications_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'enrollment, material, assignment, grade, submission'
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'is_read' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('is_read');
        $this->dbforge->create_table('notifications');
        
        // Add foreign key
        $this->db->query('ALTER TABLE notifications ADD CONSTRAINT fk_notifications_user 
                         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }
    
    public function down() {
        $this->dbforge->drop_table('notifications');
    }
}

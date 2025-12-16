<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();

        // 1. Ensure settings table exists
        if (!$this->db->table_exists('settings')) {
            $this->create_settings_table();
        }

        // 2. Ensure Email is UNIQUE in users table (Fix for Concurrency/Race Condition)
        if ($this->db->table_exists('users')) {
            // Check if index exists is hard in CI3 default, we'll straight up try to add it via SQL if not caught
            // Or easier: use dbforge to modify column
            $fields = array(
                'email' => array(
                    'name' => 'email',
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'unique' => TRUE, // This adds the constraint
                ),
            );
            // $this->dbforge->modify_column('users', $fields); 
            // modify_column with unique doesn't always work in all drivers. 
            // Let's use direct SQL for MariaDB/MySQL which is XAMPP default
            $sql = "SHOW INDEX FROM users WHERE Key_name = 'email'";
            $query = $this->db->query($sql);
            if ($query->num_rows() == 0) {
                // Add unique index if not exists
                $this->db->query("ALTER TABLE users ADD UNIQUE (email)");
            }

            // 3. Add created_by column for Audit Trail
            if (!$this->db->field_exists('created_by', 'users')) {
                $fields = array(
                    'created_by' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'null' => TRUE,
                        'after' => 'role'
                    )
                );
                $this->dbforge->add_column('users', $fields);
            }
        }
    }

    private function create_settings_table()
    {
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'setting_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => TRUE,
            ),
            'setting_value' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('settings');

        // Seed default settings
        $default_settings = [
            'system_name' => 'Learning Management System',
            'system_email' => 'admin@lms.com',
            'timezone' => 'Asia/Manila',
            'notify_registration' => '1',
            'notify_enrollment' => '1',
            'notify_assignment' => '1',
            'max_file_size' => '10',
            'max_students_per_course' => '50'
        ];

        foreach ($default_settings as $key => $value) {
            $this->save_setting($key, $value);
        }
    }

    /**
     * Get current time based on System Timzeone setting
     * Fix for Timezone Handling issue
     */
    public function get_current_timestamp()
    {
        $timezone = $this->get_setting('timezone');
        if (!$timezone) {
            $timezone = 'Asia/Manila'; // Default fallback
        }

        try {
            $date = new DateTime('now', new DateTimeZone($timezone));
            return $date->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return date('Y-m-d H:i:s'); // Fallback to server time
        }
    }

    public function get_setting($key)
    {
        $query = $this->db->get_where('settings', array('setting_key' => $key));
        $result = $query->row();
        return $result ? $result->setting_value : null;
    }

    public function get_all_settings()
    {
        $query = $this->db->get('settings');
        $results = $query->result();
        $settings = [];
        foreach ($results as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    public function save_setting($key, $value)
    {
        $data = array(
            'setting_value' => $value,
            'updated_at' => $this->get_current_timestamp()
        );

        // Check if key exists
        $existing = $this->get_setting($key);
        if ($existing !== null) {
            $this->db->where('setting_key', $key);
            return $this->db->update('settings', $data);
        } else {
            $data['setting_key'] = $key;
            return $this->db->insert('settings', $data);
        }
    }
}

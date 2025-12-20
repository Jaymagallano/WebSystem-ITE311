<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // Get all users ordered by creation date
    public function get_all_users()
    {
        return $this->db->order_by('created_at', 'DESC')->get('users')->result();
    }

    /**
     * Get users with optional server-side filtering and sorting.
     *
     * @param string|null $search Free-text search (name, email, id)
     * @param string|null $role   Filter by role (admin, teacher, student)
     * @param string      $sort   Sort key: name|email|role|date
     * @return array
     */
    public function get_users_filtered($search = null, $role = null, $sort = 'date')
    {
        if (!empty($role)) {
            $this->db->where('role', $role);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('email', $search);
            // cast id to char for LIKE search; safe because CI escapes values
            $this->db->or_like('id', $search);
            $this->db->group_end();
        }

        switch ($sort) {
            case 'name':
                $this->db->order_by('name', 'ASC');
                break;
            case 'email':
                $this->db->order_by('email', 'ASC');
                break;
            case 'role':
                $this->db->order_by('role', 'ASC');
                break;
            case 'date':
            default:
                $this->db->order_by('created_at', 'DESC');
                break;
        }

        return $this->db->get('users')->result();
    }

    // Get a specific user by ID
    public function get_user_by_id($id)
    {
        return $this->db->get_where('users', array('id' => $id))->row();
    }

    // Create a new user
    public function create_user($data)
    {
        return $this->db->insert('users', $data);
    }

    // Update an existing user
    public function update_user($id, $data)
    {
        return $this->db->where('id', $id)->update('users', $data);
    }

    // Delete a user
    public function delete_user($id)
    {
        // Handle dependencies before deleting user using transactions
        $this->db->trans_start();

        // 1. Remove from enrollments (if student)
        if ($this->db->table_exists('enrollments')) {
            $this->db->where('student_id', $id)->delete('enrollments');
        }

        // 2. Unlink from courses (if teacher)
        // We set teacher_id to NULL so the course remains but has no teacher
        if ($this->db->table_exists('courses')) {
            // Check if teacher_id allows NULL? 
            // We'll attempt update. If it fails, the transaction will rollback if strictly configured,
            // but usually we want to proceed. 
            // Ideally we should check if they created courses.
            $this->db->where('teacher_id', $id)->update('courses', ['teacher_id' => NULL]);
        }

        // 3. Delete the user
        $this->db->where('id', $id)->delete('users');

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // Count users (optional filter)
    public function count_users($where = array())
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results('users');
    }

    // Get user growth data for reports
    public function get_monthly_user_counts($months = 6)
    {
        // Calculate start date
        $start_date = date('Y-m-01', strtotime("-$months months"));

        // Query to group users by Month-Year
        $this->db->select("DATE_FORMAT(created_at, '%Y-%m') as month_key, DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as count");
        $this->db->where('created_at >=', $start_date);
        $this->db->group_by('month_key');
        $this->db->order_by('month_key', 'ASC');
        $query = $this->db->get('users');
        $results = $query->result_array();

        // Fill in missing months with 0
        $final_data = [];
        $result_map = array_column($results, 'count', 'month_key');

        for ($i = $months - 1; $i >= 0; $i--) {
            $key = date('Y-m', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months"));

            $final_data[] = [
                'month' => $label,
                'count' => isset($result_map[$key]) ? (int) $result_map[$key] : 0
            ];
        }

        return $final_data;
    }
}

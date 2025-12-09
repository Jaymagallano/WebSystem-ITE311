<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_model extends CI_Model {
    
    public function get_course_materials($course_id) {
        $this->db->where('course_id', $course_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('materials')->result();
    }
    
    public function get_material_by_id($material_id) {
        return $this->db->get_where('materials', array('id' => $material_id))->row();
    }
    
    public function create_material($data) {
        return $this->db->insert('materials', $data);
    }
    
    public function delete_material($material_id) {
        // Get material to delete file
        $material = $this->get_material_by_id($material_id);
        if ($material && file_exists($material->file_path)) {
            unlink($material->file_path);
        }
        
        return $this->db->where('id', $material_id)->delete('materials');
    }
}

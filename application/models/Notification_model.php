<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {
    
    /**
     * Create a new notification
     */
    public function create_notification($data) {
        $notification = [
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'link' => isset($data['link']) ? $data['link'] : null,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('notifications', $notification);
    }
    
    /**
     * Get unread notifications for a user
     */
    public function get_unread_notifications($user_id, $limit = 10) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('notifications')->result();
    }
    
    /**
     * Get all notifications for a user
     */
    public function get_user_notifications($user_id, $limit = 20) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('notifications')->result();
    }
    
    /**
     * Get unread count
     */
    public function get_unread_count($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results('notifications');
    }
    
    /**
     * Mark notification as read
     */
    public function mark_as_read($notification_id, $user_id = null) {
        $this->db->set('is_read', 1);
        $this->db->where('id', $notification_id);
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        
        return $this->db->update('notifications');
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public function mark_all_as_read($user_id) {
        $this->db->set('is_read', 1);
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->update('notifications');
    }
    
    /**
     * Delete old read notifications (cleanup)
     */
    public function delete_old_notifications($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime("-$days days"));
        $this->db->where('is_read', 1);
        $this->db->where('created_at <', $date);
        return $this->db->delete('notifications');
    }
    
    /**
     * Notify students when teacher uploads material
     */
    public function notify_material_upload($course_id, $material_title) {
        // Get all enrolled students (only existing users)
        $this->db->select('enrollments.student_id');
        $this->db->from('enrollments');
        $this->db->join('users', 'enrollments.student_id = users.id');
        $this->db->where('enrollments.course_id', $course_id);
        $enrollments = $this->db->get()->result();
        
        foreach ($enrollments as $enrollment) {
            $this->create_notification([
                'user_id' => $enrollment->student_id,
                'type' => 'material',
                'title' => 'New Material Available',
                'message' => "New material '$material_title' has been uploaded",
                'link' => 'student/resources/' . $course_id
            ]);
        }
    }
    
    /**
     * Notify teacher when student submits assignment
     */
    public function notify_assignment_submission($teacher_id, $student_name, $assignment_title, $assignment_id) {
        $this->create_notification([
            'user_id' => $teacher_id,
            'type' => 'submission',
            'title' => 'New Assignment Submission',
            'message' => "$student_name submitted '$assignment_title'",
            'link' => 'teacher/assignment_submissions/' . $assignment_id
        ]);
    }
    
    /**
     * Notify student when grade is posted
     */
    public function notify_grade_posted($student_id, $assignment_title, $score, $max_points) {
        $percentage = round(($score / $max_points) * 100, 2);
        $this->create_notification([
            'user_id' => $student_id,
            'type' => 'grade',
            'title' => 'Assignment Graded',
            'message' => "Your assignment '$assignment_title' has been graded: $score/$max_points ($percentage%)",
            'link' => 'student/grades'
        ]);
    }
    
    /**
     * Notify student when enrolled in course
     */
    public function notify_enrollment($student_id, $course_title, $course_id) {
        $this->create_notification([
            'user_id' => $student_id,
            'type' => 'enrollment',
            'title' => 'Successfully Enrolled',
            'message' => "You have been enrolled in '$course_title'",
            'link' => 'student/course_details/' . $course_id
        ]);
    }
    
    /**
     * Notify students of new assignment
     */
    public function notify_new_assignment($course_id, $assignment_title, $due_date) {
        // Get all enrolled students (only existing users)
        $this->db->select('enrollments.student_id');
        $this->db->from('enrollments');
        $this->db->join('users', 'enrollments.student_id = users.id');
        $this->db->where('enrollments.course_id', $course_id);
        $enrollments = $this->db->get()->result();
        
        $due_formatted = date('M d, Y', strtotime($due_date));
        
        foreach ($enrollments as $enrollment) {
            $this->create_notification([
                'user_id' => $enrollment->student_id,
                'type' => 'assignment',
                'title' => 'New Assignment Posted',
                'message' => "New assignment '$assignment_title' is due on $due_formatted",
                'link' => 'student/assignments'
            ]);
        }
    }
}

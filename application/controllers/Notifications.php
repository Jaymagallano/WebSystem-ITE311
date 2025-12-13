<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['error' => 'Not authenticated']);
                exit;
            }
            redirect('login');
        }
        
        $this->load->model('Notification_model');
    }
    
    /**
     * Get unread notifications (AJAX)
     */
    public function get_unread() {
        $user_id = $this->session->userdata('user_id');
        $notifications = $this->Notification_model->get_unread_notifications($user_id);
        $count = $this->Notification_model->get_unread_count($user_id);
        
        $response = [
            'count' => $count,
            'notifications' => []
        ];
        
        foreach ($notifications as $notif) {
            $response['notifications'][] = [
                'id' => $notif->id,
                'type' => $notif->type,
                'title' => $notif->title,
                'message' => $notif->message,
                'link' => $notif->link,
                'created_at' => $notif->created_at,
                'time_ago' => $this->time_ago($notif->created_at)
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * Get all notifications (AJAX)
     */
    public function get_all() {
        $user_id = $this->session->userdata('user_id');
        $notifications = $this->Notification_model->get_user_notifications($user_id);
        
        $response = ['notifications' => []];
        
        foreach ($notifications as $notif) {
            $response['notifications'][] = [
                'id' => $notif->id,
                'type' => $notif->type,
                'title' => $notif->title,
                'message' => $notif->message,
                'link' => $notif->link,
                'is_read' => $notif->is_read,
                'created_at' => $notif->created_at,
                'time_ago' => $this->time_ago($notif->created_at)
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * Mark notification as read (AJAX)
     */
    public function mark_read($notification_id) {
        $user_id = $this->session->userdata('user_id');
        $success = $this->Notification_model->mark_as_read($notification_id, $user_id);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Mark all as read (AJAX)
     */
    public function mark_all_read() {
        $user_id = $this->session->userdata('user_id');
        $success = $this->Notification_model->mark_all_as_read($user_id);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Helper function to convert datetime to "time ago" format
     */
    private function time_ago($datetime) {
        $timestamp = strtotime($datetime);
        $difference = time() - $timestamp;
        
        if ($difference < 60) {
            return 'Just now';
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 604800) {
            $days = floor($difference / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M d, Y', $timestamp);
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_notifications extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Notification_model');
    }
    
    public function index() {
        // Create a test notification for student user (ID 3)
        $this->Notification_model->create_notification([
            'user_id' => 3, // Student User
            'type' => 'test',
            'title' => 'Test Notification',
            'message' => 'This is a test notification',
            'link' => 'dashboard'
        ]);
        
        echo "Test notification created!";
    }
    
    public function check() {
        $this->load->model('Notification_model');
        $notifications = $this->Notification_model->get_unread_notifications(3); // Student User
        
        echo "<h2>Unread Notifications for User ID 1:</h2>";
        echo "<pre>" . print_r($notifications, true) . "</pre>";
    }
}
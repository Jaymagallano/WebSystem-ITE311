<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_notification_page extends CI_Controller {
    
    public function index() {
        // Simply load the test page view
        $this->load->view('test_notification_page');
    }
}
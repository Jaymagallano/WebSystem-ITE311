<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is logged in and is a teacher
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page.');
            redirect('login');
        }
        
        if ($this->session->userdata('role') !== 'teacher') {
            $this->session->set_flashdata('error', 'Access denied. Teachers only.');
            redirect('dashboard');
        }
        
        $this->load->model('Course_model');
        $this->load->model('Assignment_model');
        $this->load->model('Grade_model');
        $this->load->model('Material_model');
        $this->load->model('Notification_model');
        $this->load->model('Student_model');
    }
    
    public function courses() {
        $data['user'] = $this->session->userdata();
        $courses = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        
        // Add counts for each course
        foreach ($courses as $course) {
            // Count enrolled students
            $course->student_count = count($this->Course_model->get_enrolled_students($course->id));
            
            // Count assignments
            $course->assignment_count = $this->db->where('course_id', $course->id)->count_all_results('assignments');
            
            // Count materials
            $course->material_count = $this->db->where('course_id', $course->id)->count_all_results('materials');
        }
        
        $data['courses'] = $courses;
        $this->load->view('teacher/courses', $data);
    }
    
    public function create_course() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Course Title', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('code', 'Course Code', 'required|trim|is_unique[courses.code]');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'code' => strtoupper($this->input->post('code')),
                    'teacher_id' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Course_model->create_course($data)) {
                    $this->session->set_flashdata('success', 'Course created successfully!');
                    redirect('teacher/courses');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create course.');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $this->load->view('teacher/create_course', $data);
    }
    
    public function edit_course($course_id) {
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Course not found or access denied.');
            redirect('teacher/courses');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Course Title', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Course_model->update_course($course_id, $data)) {
                    $this->session->set_flashdata('success', 'Course updated successfully!');
                    redirect('teacher/courses');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        $this->load->view('teacher/edit_course', $data);
    }
    
    public function students($course_id = null) {
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters - prioritize GET parameter over URL segment
        $course_filter = $this->input->get('course_id') ?: $course_id;
        $search = $this->input->get('search');
        $status = $this->input->get('status');
        $sort_by = $this->input->get('sort_by') ?: 'name';
        $sort_order = $this->input->get('sort_order') ?: 'asc';
        
        if ($course_filter) {
            $data['course'] = $this->Course_model->get_course_by_id($course_filter);
            $this->db->select('users.*, enrollments.enrolled_at');
            $this->db->from('users');
            $this->db->join('enrollments', 'users.id = enrollments.student_id');
            $this->db->where('enrollments.course_id', $course_filter);
            $this->db->where('users.role', 'student');
            
            // Apply search filter
            if ($search) {
                $this->db->group_start();
                $this->db->like('users.name', $search);
                $this->db->or_like('users.email', $search);
                $this->db->or_like('users.id', $search);
                $this->db->group_end();
            }
            
            // Apply sorting
            switch($sort_by) {
                case 'name':
                    $this->db->order_by('users.name', $sort_order);
                    break;
                case 'email':
                    $this->db->order_by('users.email', $sort_order);
                    break;
                case 'enrolled_date':
                    $this->db->order_by('enrollments.enrolled_at', $sort_order);
                    break;
                default:
                    $this->db->order_by('users.name', 'asc');
            }
            
            $data['students'] = $this->db->get()->result();
        } else {
            $this->db->select('users.*, users.created_at');
            $this->db->from('users');
            $this->db->where('role', 'student');
            
            // Apply search filter
            if ($search) {
                $this->db->group_start();
                $this->db->like('name', $search);
                $this->db->or_like('email', $search);
                $this->db->or_like('id', $search);
                $this->db->group_end();
            }
            
            // Apply sorting
            switch($sort_by) {
                case 'name':
                    $this->db->order_by('name', $sort_order);
                    break;
                case 'email':
                    $this->db->order_by('email', $sort_order);
                    break;
                case 'registered_date':
                    $this->db->order_by('created_at', $sort_order);
                    break;
                default:
                    $this->db->order_by('name', 'asc');
            }
            
            $data['students'] = $this->db->get()->result();
        }
        
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $data['search'] = $search;
        $data['status'] = $status;
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['course_filter'] = $course_filter;
        
        // Check if AJAX request
        if ($this->input->is_ajax_request()) {
            // Return JSON response for AJAX
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'students' => $data['students'],
                    'count' => count($data['students']),
                    'has_course' => isset($data['course'])
                ]));
        } else {
            $this->load->view('teacher/students', $data);
        }
    }
    
    public function students_ajax($course_id = null) {
        // Get filter parameters - prioritize POST parameter
        $course_filter = $this->input->post('course_id') ?: $course_id;
        $search = $this->input->post('search');
        $status = $this->input->post('status');
        $sort_by = $this->input->post('sort_by') ?: 'name';
        $sort_order = $this->input->post('sort_order') ?: 'asc';
        
        if ($course_filter) {
            $course = $this->Course_model->get_course_by_id($course_filter);
            $this->db->select('users.*, enrollments.enrolled_at');
            $this->db->from('users');
            $this->db->join('enrollments', 'users.id = enrollments.student_id');
            $this->db->where('enrollments.course_id', $course_filter);
            $this->db->where('users.role', 'student');
            
            // Apply search filter
            if ($search) {
                $this->db->group_start();
                $this->db->like('users.name', $search);
                $this->db->or_like('users.email', $search);
                $this->db->or_like('users.id', $search);
                $this->db->group_end();
            }
            
            // Apply sorting
            switch($sort_by) {
                case 'name':
                    $this->db->order_by('users.name', $sort_order);
                    break;
                case 'email':
                    $this->db->order_by('users.email', $sort_order);
                    break;
                case 'enrolled_date':
                    $this->db->order_by('enrollments.enrolled_at', $sort_order);
                    break;
                default:
                    $this->db->order_by('users.name', 'asc');
            }
            
            $students = $this->db->get()->result();
            $has_course = true;
        } else {
            $course = null;
            $this->db->select('users.*, users.created_at');
            $this->db->from('users');
            $this->db->where('role', 'student');
            
            // Apply search filter
            if ($search) {
                $this->db->group_start();
                $this->db->like('name', $search);
                $this->db->or_like('email', $search);
                $this->db->or_like('id', $search);
                $this->db->group_end();
            }
            
            // Apply sorting
            switch($sort_by) {
                case 'name':
                    $this->db->order_by('name', $sort_order);
                    break;
                case 'email':
                    $this->db->order_by('email', $sort_order);
                    break;
                case 'registered_date':
                    $this->db->order_by('created_at', $sort_order);
                    break;
                default:
                    $this->db->order_by('name', 'asc');
            }
            
            $students = $this->db->get()->result();
            $has_course = false;
        }
        
        // Build HTML response
        $html = '';
        if (count($students) > 0) {
            foreach ($students as $student) {
                $html .= '<tr>';
                $html .= '<td><strong>#' . $student->id . '</strong></td>';
                $html .= '<td><i class="bi bi-person-circle text-primary"></i> <strong>' . $student->name . '</strong></td>';
                $html .= '<td><i class="bi bi-envelope"></i> ' . $student->email . '</td>';
                
                if ($has_course) {
                    // Enrolled date
                    $enrolled_date = isset($student->enrolled_at) ? date('M d, Y', strtotime($student->enrolled_at)) : 'N/A';
                    $html .= '<td><i class="bi bi-calendar-check"></i> ' . $enrolled_date . '</td>';
                    
                    // Actions
                    $html .= '<td>';
                    $html .= '<div class="btn-group btn-group-sm">';
                    $html .= '<a href="' . base_url('teacher/student_profile/' . $student->id) . '" class="btn btn-outline-info" title="View Profile"><i class="bi bi-eye"></i></a>';
                    $html .= '<a href="' . base_url('teacher/student_grades/' . $course_filter . '/' . $student->id) . '" class="btn btn-outline-primary" title="View Grades"><i class="bi bi-bar-chart"></i></a>';
                    $html .= '<a href="' . base_url('teacher/unenroll_student/' . $course_filter . '/' . $student->id) . '" class="btn btn-outline-danger" title="Unenroll" onclick="return confirm(\'Are you sure?\')"><i class="bi bi-x-circle"></i></a>';
                    $html .= '</div>';
                    $html .= '</td>';
                } else {
                    // Registered date
                    $html .= '<td><i class="bi bi-calendar-plus"></i> ' . date('M d, Y', strtotime($student->created_at)) . '</td>';
                    
                    // Actions
                    $html .= '<td><button class="btn btn-sm btn-outline-info" title="View Details" onclick="alert(\'Student ID: ' . $student->id . '\\nName: ' . $student->name . '\\nEmail: ' . $student->email . '\')"><i class="bi bi-eye"></i></button></td>';
                }
                
                $html .= '</tr>';
            }
        } else {
            $colspan = $has_course ? '5' : '5';
            $html .= '<tr><td colspan="' . $colspan . '" class="text-center py-5">';
            $html .= '<i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>';
            $html .= '<p class="mt-2 text-muted">';
            if ($search) {
                $html .= 'No students found matching your search criteria.';
            } elseif ($has_course) {
                $html .= 'No students enrolled in this course yet.';
            } else {
                $html .= 'No students found.';
            }
            $html .= '</p></td></tr>';
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'html' => $html,
                'count' => count($students)
            ]));
    }
    
    public function assignments() {
        $data['user'] = $this->session->userdata();
        $data['assignments'] = $this->Assignment_model->get_teacher_assignments($this->session->userdata('user_id'));
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/assignments', $data);
    }
    
    public function assignment_submissions($assignment_id) {
        // Get assignment details
        $assignment = $this->Assignment_model->get_assignment_by_id($assignment_id);
        
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('teacher/assignments');
        }
        
        // Verify assignment belongs to teacher's course
        $course = $this->Course_model->get_course_by_id($assignment->course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/assignments');
        }
        
        $data['user'] = $this->session->userdata();
        $data['assignment'] = $assignment;
        $data['course'] = $course;
        $data['submissions'] = $this->Assignment_model->get_assignment_submissions($assignment_id);
        
        // Get enrolled students count
        $enrolled_students = $this->Course_model->get_enrolled_students($assignment->course_id);
        $data['total_students'] = count($enrolled_students);
        $data['submitted_count'] = count($data['submissions']);
        
        $this->load->view('teacher/assignment_submissions', $data);
    }
    
    public function assignment_stats($assignment_id) {
        $assignment = $this->Assignment_model->get_assignment_by_id($assignment_id);
        
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('teacher/assignments');
        }
        
        // Verify assignment belongs to teacher's course
        $course = $this->Course_model->get_course_by_id($assignment->course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/assignments');
        }
        
        $data['user'] = $this->session->userdata();
        $data['assignment'] = $assignment;
        $data['course'] = $course;
        $submissions = $this->Assignment_model->get_assignment_submissions($assignment_id);
        
        // Calculate statistics
        $enrolled_students = $this->Course_model->get_enrolled_students($assignment->course_id);
        $data['total_students'] = count($enrolled_students);
        $data['submitted_count'] = count($submissions);
        $data['pending_count'] = $data['total_students'] - $data['submitted_count'];
        
        // Calculate grading stats
        $graded_submissions = array_filter($submissions, function($sub) {
            return $sub->status === 'graded' && $sub->score !== null;
        });
        $data['graded_count'] = count($graded_submissions);
        $data['ungraded_count'] = $data['submitted_count'] - $data['graded_count'];
        
        // Debug logging
        log_message('debug', 'Assignment Stats - Total submissions: ' . count($submissions));
        log_message('debug', 'Assignment Stats - Graded count: ' . $data['graded_count']);
        
        // Calculate grade statistics
        if ($data['graded_count'] > 0) {
            $grades = array_values(array_map(function($sub) {
                return floatval($sub->points_earned);
            }, $graded_submissions));
            
            log_message('debug', 'Assignment Stats - Grades: ' . json_encode($grades));
            
            $data['average_grade'] = array_sum($grades) / count($grades);
            $data['highest_grade'] = max($grades);
            $data['lowest_grade'] = min($grades);
            
            // Calculate percentage
            if ($assignment->max_points > 0) {
                $data['average_percentage'] = ($data['average_grade'] / $assignment->max_points) * 100;
            } else {
                $data['average_percentage'] = 0;
            }
        } else {
            $data['average_grade'] = 0;
            $data['highest_grade'] = 0;
            $data['lowest_grade'] = 0;
            $data['average_percentage'] = 0;
        }
        
        $data['submissions'] = $submissions;
        
        $this->load->view('teacher/assignment_stats', $data);
    }
    
    public function create_assignment() {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Assignment Title', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('course_id', 'Course', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('max_points', 'Max Points', 'required|numeric');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'course_id' => $this->input->post('course_id'),
                    'due_date' => $this->input->post('due_date'),
                    'max_points' => $this->input->post('max_points'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Assignment_model->create_assignment($data)) {
                    // Notify enrolled students about new assignment
                    $this->Notification_model->notify_new_assignment(
                        $this->input->post('course_id'),
                        $this->input->post('title'),
                        $this->input->post('due_date')
                    );
                    
                    $this->session->set_flashdata('success', 'Assignment created successfully!');
                    redirect('teacher/assignments');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/create_assignment', $data);
    }
    
    public function grades($course_id = null) {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        
        // Get course from query parameter or URL segment
        $course_filter = $this->input->get('course_id') ?: $course_id;
        
        if ($course_filter) {
            $data['selected_course'] = $this->Course_model->get_course_by_id($course_filter);
            $data['students'] = $this->Course_model->get_enrolled_students($course_filter);
            $data['assignments'] = $this->Assignment_model->get_course_assignments($course_filter);
            
            // Calculate average grade for each student
            foreach ($data['students'] as $student) {
                $student->average_grade = $this->Grade_model->get_student_average_grade($student->id, $course_filter);
            }
        }
        
        $data['course_filter'] = $course_filter;
        $this->load->view('teacher/grades', $data);
    }
    
    public function grades_ajax() {
        $course_id = $this->input->post('course_id');
        
        if (!$course_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Course ID is required'
                ]));
            return;
        }
        
        $course = $this->Course_model->get_course_by_id($course_id);
        $students = $this->Course_model->get_enrolled_students($course_id);
        $assignments = $this->Assignment_model->get_course_assignments($course_id);
        
        // Calculate average grade for each student
        foreach ($students as $student) {
            $student->average_grade = $this->Grade_model->get_student_average_grade($student->id, $course_id);
        }
        
        // Build HTML response
        $html = '';
        if (count($students) > 0) {
            foreach ($students as $student) {
                $html .= '<tr>';
                $html .= '<td><i class="bi bi-person-circle"></i> ' . $student->name . '</td>';
                $html .= '<td>' . $student->email . '</td>';
                $html .= '<td>';
                
                if ($student->average_grade !== null) {
                    $grade = $student->average_grade;
                    $badge_class = 'bg-success';
                    if ($grade < 60) {
                        $badge_class = 'bg-danger';
                    } elseif ($grade < 75) {
                        $badge_class = 'bg-warning';
                    } elseif ($grade < 85) {
                        $badge_class = 'bg-info';
                    }
                    $html .= '<span class="badge ' . $badge_class . '">' . number_format($grade, 2) . '%</span>';
                } else {
                    $html .= '<span class="badge bg-secondary">No grades yet</span>';
                }
                
                $html .= '</td>';
                $html .= '<td><a href="' . base_url('teacher/student_grades/' . $course_id . '/' . $student->id) . '" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View Details</a></td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="4" class="text-center py-4">No students enrolled in this course.</td></tr>';
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'html' => $html,
                'course_title' => $course->title,
                'student_count' => count($students)
            ]));
    }
    
    public function student_grades($course_id, $student_id) {
        // Verify course belongs to teacher
        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/grades');
        }
        
        // Verify student is enrolled in course
        if (!$this->Course_model->is_enrolled($student_id, $course_id)) {
            $this->session->set_flashdata('error', 'Student not enrolled in this course.');
            redirect('teacher/grades/' . $course_id);
        }
        
        if ($this->input->method() == 'post') {
            $assignment_id = $this->input->post('assignment_id');
            $points = $this->input->post('points');
            $feedback = $this->input->post('feedback');
            
            // We need to find the submission ID first
            $this->db->where('assignment_id', $assignment_id);
            $this->db->where('student_id', $student_id);
            $submission = $this->db->get('assignment_submissions')->row();
            
            if ($submission && $this->Grade_model->update_grade($submission->id, $points, $feedback)) {
                // Notify student about graded assignment
                $assignment = $this->Assignment_model->get_assignment_by_id($assignment_id);
                $student_info = $this->db->where('id', $student_id)->get('users')->row();
                
                if ($assignment && $student_info) {
                    $this->Notification_model->notify_grade_posted(
                        $student_id,
                        $assignment->title,
                        $points,
                        $assignment_id
                    );
                }
                
                $this->session->set_flashdata('success', 'Grade saved successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to save grade.');
            }
            
            redirect('teacher/student_grades/' . $course_id . '/' . $student_id);
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        $data['student'] = $this->db->where('id', $student_id)->get('users')->row();
        $data['assignments'] = $this->Assignment_model->get_course_assignments($course_id);
        $data['submissions'] = $this->Grade_model->get_course_grades($course_id, $student_id) ?: [];
        $data['average_grade'] = $this->Grade_model->get_student_average_grade($student_id, $course_id) ?? 0;
        
        $this->load->view('teacher/student_grades', $data);
    }
    
    public function materials($course_id = null) {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        
        // Get course from query parameter or URL segment
        $course_filter = $this->input->get('course_id') ?: $course_id;
        
        if ($course_filter) {
            $data['selected_course'] = $this->Course_model->get_course_by_id($course_filter);
            $data['materials'] = $this->Material_model->get_course_materials($course_filter);
        }
        
        $data['course_filter'] = $course_filter;
        $this->load->view('teacher/materials', $data);
    }
    
    public function materials_ajax() {
        $course_id = $this->input->post('course_id');
        
        if (!$course_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Course ID is required'
                ]));
            return;
        }
        
        $course = $this->Course_model->get_course_by_id($course_id);
        $materials = $this->Material_model->get_course_materials($course_id);
        
        // Build HTML response
        $html = '';
        if (count($materials) > 0) {
            foreach ($materials as $material) {
                // Use relative path for download
                $download_url = base_url('uploads/materials/' . $material->file_name);
                
                $html .= '<tr>';
                $html .= '<td><i class="bi bi-file-earmark-text"></i> <strong>' . $material->title . '</strong></td>';
                $html .= '<td>' . $material->description . '</td>';
                $html .= '<td><span class="badge bg-info">' . strtoupper(pathinfo($material->file_name, PATHINFO_EXTENSION)) . '</span></td>';
                $html .= '<td>' . date('M d, Y', strtotime($material->created_at)) . '</td>';
                $html .= '<td>';
                $html .= '<a href="' . $download_url . '" class="btn btn-sm btn-outline-primary" download><i class="bi bi-download"></i></a> ';
                $html .= '<a href="' . base_url('teacher/delete_material/' . $material->id) . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete this material?\')"><i class="bi bi-trash"></i></a>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" class="text-center py-4">No materials uploaded yet for this course.</td></tr>';
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'html' => $html,
                'course_title' => $course->title,
                'course_code' => $course->code,
                'material_count' => count($materials)
            ]));
    }
    
    public function upload_material() {
        if ($this->input->method() == 'post') {
            $course_id = $this->input->post('course_id');
            
            // Verify course belongs to teacher
            $course = $this->Course_model->get_course_by_id($course_id);
            if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('error', 'Access denied.');
                redirect('teacher/materials');
            }
            
            $config['upload_path'] = './uploads/materials/';
            $config['allowed_types'] = 'pdf|doc|docx|ppt|pptx|xls|xlsx|txt|zip';
            $config['max_size'] = 10240; // 10MB
            
            $this->load->library('upload', $config);
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'file_name' => $upload_data['file_name'],
                    'file_path' => 'uploads/materials/' . $upload_data['file_name'],
                    'file_type' => $upload_data['file_type'],
                    'course_id' => $course_id,
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Material_model->create_material($data)) {
                    // Notify enrolled students about new material
                    $this->Notification_model->notify_material_upload(
                        $course_id,
                        $this->input->post('title')
                    );
                    
                    $this->session->set_flashdata('success', 'Material uploaded successfully!');
                    redirect('teacher/materials/' . $course_id);
                }
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        $this->load->view('teacher/upload_material', $data);
    }
    
    public function delete_material($material_id) {
        // Get material first
        $material = $this->Material_model->get_material_by_id($material_id);
        
        if (!$material) {
            $this->session->set_flashdata('error', 'Material not found.');
            redirect('teacher/materials');
            return;
        }
        
        // Store course_id before deletion
        $course_id = $material->course_id;
        
        // Verify material belongs to teacher's course
        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied. You can only delete materials from your own courses.');
            redirect('teacher/materials');
            return;
        }
        
        // Attempt to delete
        if ($this->Material_model->delete_material($material_id)) {
            // Delete file from server if it exists
            if (file_exists($material->file_path)) {
                @unlink($material->file_path);
            }
            
            $this->session->set_flashdata('success', 'Material deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete material from database.');
        }
        
        redirect('teacher/materials/' . $course_id);
    }
    
    public function grade_submission($submission_id) {
        // Get submission details
        $this->db->select('assignment_submissions.*, assignments.title as assignment_title, assignments.max_points, assignments.course_id, users.name as student_name');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->join('users', 'assignment_submissions.student_id = users.id');
        $this->db->where('assignment_submissions.id', $submission_id);
        $submission = $this->db->get()->row();
        
        if (!$submission) {
            $this->session->set_flashdata('error', 'Submission not found.');
            redirect('teacher/assignments');
        }
        
        // Verify assignment belongs to teacher's course
        $course = $this->Course_model->get_course_by_id($submission->course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/assignments');
        }
        
        if ($this->input->method() == 'post') {
            $score = $this->input->post('score');
            $feedback = $this->input->post('feedback');
            
            // Debug logging
            log_message('debug', 'Grade Submission - ID: ' . $submission_id . ', Score: ' . $score . ', Feedback: ' . $feedback);
            
            // Validate score
            if ($score === null || $score === '') {
                $this->session->set_flashdata('error', 'Score is required.');
                redirect('teacher/grade_submission/' . $submission_id . ($this->input->get('return') ? '?return=' . $this->input->get('return') : ''));
                return;
            }
            
            if ($score < 0 || $score > $submission->max_points) {
                $this->session->set_flashdata('error', 'Score must be between 0 and ' . $submission->max_points);
                redirect('teacher/grade_submission/' . $submission_id . ($this->input->get('return') ? '?return=' . $this->input->get('return') : ''));
                return;
            }
            
            $update_result = $this->Grade_model->update_grade($submission->id, $score, $feedback);
            log_message('debug', 'Grade Update Result: ' . ($update_result ? 'SUCCESS' : 'FAILED'));
            
            if ($update_result) {
                // Notify student about graded assignment
                try {
                    $this->Notification_model->notify_grade_posted(
                        $submission->student_id,
                        $submission->assignment_title,
                        $score,
                        $submission->assignment_id
                    );
                } catch (Exception $e) {
                    log_message('error', 'Notification failed: ' . $e->getMessage());
                }
                
                $this->session->set_flashdata('success', 'Assignment graded successfully!');
                
                // Redirect back to referrer if provided, otherwise to submissions page
                $return_url = $this->input->get('return');
                if ($return_url === 'stats') {
                    redirect('teacher/assignment_stats/' . $submission->assignment_id);
                } else {
                    redirect('teacher/assignment_submissions/' . $submission->assignment_id);
                }
            } else {
                $this->session->set_flashdata('error', 'Failed to update grade in database. Please try again.');
                redirect('teacher/grade_submission/' . $submission_id . ($this->input->get('return') ? '?return=' . $this->input->get('return') : ''));
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['submission'] = $submission;
        $data['course'] = $course;
        $this->load->view('teacher/grade_submission', $data);
    }
    
    public function edit_assignment($assignment_id) {
        // Get assignment details
        $assignment = $this->Assignment_model->get_assignment_by_id($assignment_id);
        
        if (!$assignment) {
            $this->session->set_flashdata('error', 'Assignment not found.');
            redirect('teacher/assignments');
        }
        
        // Verify assignment belongs to teacher's course
        $course = $this->Course_model->get_course_by_id($assignment->course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/assignments');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Assignment Title', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('max_points', 'Max Points', 'required|numeric');
            
            if ($this->form_validation->run()) {
                $data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'due_date' => $this->input->post('due_date'),
                    'max_points' => $this->input->post('max_points'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Assignment_model->update_assignment($assignment_id, $data)) {
                    $this->session->set_flashdata('success', 'Assignment updated successfully!');
                    redirect('teacher/assignments');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update assignment.');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['assignment'] = $assignment;
        $this->load->view('teacher/edit_assignment', $data);
    }
    
    public function notifications() {
        $data['user'] = $this->session->userdata();
        $this->load->view('teacher/notifications', $data);
    }
    
    // ==================== STUDENT PROFILE MANAGEMENT ====================
    
    public function student_profile($student_id) {
        $student = $this->Student_model->get_student_by_id($student_id);
        
        if (!$student) {
            $this->session->set_flashdata('error', 'Student not found.');
            redirect('teacher/students');
        }
        
        $data['user'] = $this->session->userdata();
        $data['student'] = $student;
        $data['courses'] = $this->Student_model->get_student_courses($student_id);
        $data['recent_submissions'] = $this->Student_model->get_student_submissions($student_id, 5);
        $data['stats'] = $this->Student_model->get_student_stats($student_id);
        
        $this->load->view('teacher/student_profile', $data);
    }
    
    public function edit_student($student_id) {
        $student = $this->Student_model->get_student_by_id($student_id);
        
        if (!$student) {
            $this->session->set_flashdata('error', 'Student not found.');
            redirect('teacher/students');
        }
        
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
            
            if ($this->form_validation->run()) {
                $update_data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                // Handle profile photo upload
                if (!empty($_FILES['profile_photo']['name'])) {
                    $config['upload_path'] = './uploads/profiles/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048; // 2MB
                    $config['file_name'] = 'student_' . $student_id . '_' . time();
                    
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }
                    
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload('profile_photo')) {
                        $upload_data = $this->upload->data();
                        $update_data['profile_photo'] = $upload_data['file_name'];
                        
                        // Delete old photo if exists
                        if ($student->profile_photo && file_exists('./uploads/profiles/' . $student->profile_photo)) {
                            @unlink('./uploads/profiles/' . $student->profile_photo);
                        }
                    }
                }
                
                if ($this->Student_model->update_student($student_id, $update_data)) {
                    $this->session->set_flashdata('success', 'Student information updated successfully!');
                    redirect('teacher/student_profile/' . $student_id);
                } else {
                    $this->session->set_flashdata('error', 'Failed to update student information.');
                }
            }
        }
        
        $data['user'] = $this->session->userdata();
        $data['student'] = $student;
        $this->load->view('teacher/edit_student', $data);
    }
    
    // ==================== ENROLLMENT MANAGEMENT ====================
    
    // ==================== CSV IMPORT/EXPORT ====================
    
    public function export_students() {
        $course_id = $this->input->get('course_id');
        
        if ($course_id) {
            // Export students from specific course
            $course = $this->Course_model->get_course_by_id($course_id);
            if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('error', 'Access denied.');
                redirect('teacher/students');
            }
            
            $students = $this->Course_model->get_enrolled_students($course_id);
            $filename = 'students_' . $course->code . '_' . date('Y-m-d') . '.csv';
        } else {
            // Export all students
            $students = $this->Student_model->get_all_students();
            $filename = 'all_students_' . date('Y-m-d') . '.csv';
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['ID', 'Name', 'Email', 'Status', 'Registered Date']);
        
        // Add data
        foreach ($students as $student) {
            fputcsv($output, [
                $student->id,
                $student->name,
                $student->email,
                $student->status ?? 'active',
                date('Y-m-d', strtotime($student->created_at))
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    // ==================== REPORTS & ANALYTICS ====================
    
    public function reports() {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->get_teacher_courses($this->session->userdata('user_id'));
        
        // Get overall statistics
        $teacher_id = $this->session->userdata('user_id');
        
        // Total students across all courses
        $this->db->select('COUNT(DISTINCT enrollments.student_id) as total');
        $this->db->from('enrollments');
        $this->db->join('courses', 'enrollments.course_id = courses.id');
        $this->db->where('courses.teacher_id', $teacher_id);
        $result = $this->db->get()->row();
        $data['total_students'] = $result->total;
        
        // Total assignments
        $this->db->where('course_id IN (SELECT id FROM courses WHERE teacher_id = ' . $teacher_id . ')', NULL, FALSE);
        $data['total_assignments'] = $this->db->count_all_results('assignments');
        
        // Total submissions
        $this->db->select('COUNT(*) as total');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->where('courses.teacher_id', $teacher_id);
        $result = $this->db->get()->row();
        $data['total_submissions'] = $result->total;
        
        // Average grade across all courses
        $this->db->select_avg('assignment_submissions.points_earned');
        $this->db->from('assignment_submissions');
        $this->db->join('assignments', 'assignment_submissions.assignment_id = assignments.id');
        $this->db->join('courses', 'assignments.course_id = courses.id');
        $this->db->where('courses.teacher_id', $teacher_id);
        $this->db->where('assignment_submissions.status', 'graded');
        $result = $this->db->get()->row();
        $data['average_grade'] = $result->points_earned ?? 0;
        
        $this->load->view('teacher/reports', $data);
    }
    
    public function course_report($course_id) {
        // Verify course belongs to teacher
        $course = $this->Course_model->get_course_by_id($course_id);
        if (!$course || $course->teacher_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('teacher/reports');
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        
        // Get enrolled students
        $students = $this->Course_model->get_enrolled_students($course_id);
        $data['total_students'] = count($students);
        
        // Get assignments
        $assignments = $this->Assignment_model->get_course_assignments($course_id);
        $data['total_assignments'] = count($assignments);
        
        // Calculate performance metrics
        $performance_data = [];
        foreach ($students as $student) {
            $avg_grade = $this->Grade_model->get_student_average_grade($student->id, $course_id);
            $performance_data[] = [
                'student' => $student,
                'average_grade' => $avg_grade
            ];
        }
        
        $data['performance_data'] = $performance_data;
        
        // Calculate class statistics
        $grades = array_filter(array_column($performance_data, 'average_grade'));
        if (!empty($grades)) {
            $data['class_average'] = array_sum($grades) / count($grades);
            $data['highest_grade'] = max($grades);
            $data['lowest_grade'] = min($grades);
        } else {
            $data['class_average'] = 0;
            $data['highest_grade'] = 0;
            $data['lowest_grade'] = 0;
        }
        
        $this->load->view('teacher/course_report', $data);
    }
}
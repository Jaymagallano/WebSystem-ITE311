<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth/login';
$route['about'] = 'home/about';
$route['contact'] = 'home/contact';

// Authentication Routes
$route['register'] = 'auth/register';
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'auth/dashboard';

// Teacher Routes
$route['teacher/courses'] = 'teacher/courses';
$route['teacher/create_course'] = 'teacher/create_course';
$route['teacher/edit_course/(:num)'] = 'teacher/edit_course/$1';
$route['teacher/students'] = 'teacher/students';
$route['teacher/students/(:num)'] = 'teacher/students/$1';
$route['teacher/assignments'] = 'teacher/assignments';
$route['teacher/create_assignment'] = 'teacher/create_assignment';
$route['teacher/assignment_submissions/(:num)'] = 'teacher/assignment_submissions/$1';
$route['teacher/assignment_stats/(:num)'] = 'teacher/assignment_stats/$1';
$route['teacher/grades'] = 'teacher/grades';
$route['teacher/grades/(:num)'] = 'teacher/grades/$1';
$route['teacher/student_grades/(:num)/(:num)'] = 'teacher/student_grades/$1/$2';
$route['teacher/grade_submission/(:num)'] = 'teacher/grade_submission/$1';
$route['teacher/materials'] = 'teacher/materials';
$route['teacher/materials/(:num)'] = 'teacher/materials/$1';
$route['teacher/upload_material'] = 'teacher/upload_material';
$route['teacher/delete_material/(:num)'] = 'teacher/delete_material/$1';
$route['teacher/notifications'] = 'teacher/notifications';

// Student Routes
$route['student/courses'] = 'student/courses';
$route['student/enroll/(:num)'] = 'student/enroll/$1';
$route['student/course_details/(:num)'] = 'student/course_details/$1';
$route['student/assignments'] = 'student/assignments';
$route['student/submit_assignment/(:num)'] = 'student/submit_assignment/$1';
$route['student/grades'] = 'student/grades';
$route['student/schedule'] = 'student/schedule';
$route['student/resources'] = 'student/resources';
$route['student/resources/(:num)'] = 'student/resources/$1';
$route['student/download_material/(:num)'] = 'student/download_material/$1';
$route['student/notifications'] = 'student/notifications';

// Admin Routes
$route['admin/users'] = 'admin/users';
$route['admin/create_user'] = 'admin/create_user';
$route['admin/edit_user/(:num)'] = 'admin/edit_user/$1';
$route['admin/delete_user/(:num)'] = 'admin/delete_user/$1';
$route['admin/courses'] = 'admin/courses';
$route['admin/delete_course/(:num)'] = 'admin/delete_course/$1';
$route['admin/settings'] = 'admin/settings';
$route['admin/reports'] = 'admin/reports';
$route['admin/notifications'] = 'admin/notifications';

// Notification Routes (AJAX)
$route['notifications/get_unread'] = 'notifications/get_unread';
$route['notifications/get_all'] = 'notifications/get_all';
$route['notifications/mark_read/(:num)'] = 'notifications/mark_read/$1';
$route['notifications/mark_all_read'] = 'notifications/mark_all_read';

// Test Notification Routes
$route['test_notifications'] = 'test_notifications/index';
$route['test_notifications/check'] = 'test_notifications/check';
$route['test_notification_page'] = 'test_notification_page';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');  // Set home route properly

// Authentication Routes
$routes->get('user/login', 'UserController::login');
$routes->post('user/login', 'UserController::loginPost');
$routes->get('/logout', 'AuthController::logout');

// User Routes
$routes->post('user/authenticate', 'UserController::authenticate');
$routes->get('user/dashboard', 'UserController::dashboard', ['filter' => 'auth']);
$routes->get('user/submit_ticket', 'UserController::submit_ticket');
$routes->post('user/submit_ticket', 'UserController::submitticket');
$routes->get('tickets', 'TicketController::viewTickets');
$routes->get('user/check_ticket', 'UserController::checkTicketForm'); 
$routes->get('user/login', 'UserController::login');
$routes->post('user/loginPost', 'UserController::loginPost');
$routes->get('ticket/status/(:num)', 'TicketController::status/$1');
$routes->post('user/check_ticket_status', 'UserController::checkTicketStatus');
$routes->get('user/ticket_success/(:any)', 'UserController::ticketSuccess/$1');
$routes->get('user/ticket_status/(:any)', 'UserController::ticketStatus/$1');
$routes->get('user/ticket_status', 'UserController::ticketStatus');
$routes->get('user/message/(:segment)/(:segment)', 'UserController::message/$1/$2');
$routes->post('user/sendMessage', 'UserController::sendMessage');
$routes->get('user/getMessages', 'UserController::getMessages');


// Admin Routes
$routes->get('admin/login', 'AdminController::login');
$routes->post('admin/authenticate', 'AdminController::authenticate');
$routes->get('admin/logout', 'AdminController::logout');
$routes->get('admin/dashboard', 'AdminController::dashboard', ['filter' => 'auth']);
$routes->post('admin/update-ticket', 'AdminController::update_ticket', ['filter' => 'auth']);
$routes->get('admin/ticket', 'AdminController::ticket');
$routes->get('admin/profile', 'AdminController::profile');
$routes->post('admin/update-profile', 'AdminController::updateProfile');
$routes->get('admin/resolve_ticket/(:num)', 'AdminController::resolveTicket/$1');
$routes->post('admin/sendMessage', 'AdminController::sendMessage');
$routes->get('admin-messages/getMessages/(:segment)', 'AdminController::getMessages/$1');
$routes->get('admin/messages/(:segment)', 'AdminController::viewMessages/$1');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('chart/getChartData', 'ChartController::getChartData');
$routes->get('admin/getTicketData', 'ChartController::getTicketData');
$routes->get('dashboard/trend-data/(:num)', 'Dashboard::trendData/$1');
$routes->get('uploads/messages/(:any)', 'FileController::serveMessage/$1');
$routes->get('admin/filter-tickets', 'AdminController::filterTickets');
$routes->get('admin/getTicketActivity', 'AdminController::getTicketActivity');
$routes->get('admin/getLogs', 'AdminController::getLogs');

// Superadmin Routes
$routes->get('/superadmin/login', 'SuperadminController::login');
$routes->post('/superadmin/authenticate', 'SuperadminController::authenticate');
$routes->get('/superadmin/dashboard', 'SuperadminController::index', ['filter' => 'auth']);
$routes->get('superadmin/ticket', 'SuperAdminController::ticket');
$routes->get('superadmin/profile', 'SuperAdminController::profile');
// Superadmin Routes for Managing Admins
$routes->get('superadmin/listAdmin', 'SuperadminController::listAdmins');
$routes->post('superadmin/editAdmin', 'SuperadminController::editAdmin');
$routes->post('superadmin/editAdmin/(:num)', 'SuperadminController::editAdmin/$1');
$routes->get('superadmin/delete-admin/(:num)', 'SuperadminController::deleteAdmin/$1', ['filter' => 'authGuard']);
$routes->post('superadmin/assign-ticket', 'SuperadminController::assignTicket', ['filter' => 'authGuard']);
$routes->get('superadmin/manageAdmins', 'SuperadminController::listAdmins');
$routes->post('superadmin/assignAdmin', 'SuperAdminController::assignAdmin');
$routes->get('ticket/getTicketHistory/(:num)', 'superadminController::getTicketHistory/$1');


// Uploads Route
$routes->get('uploads/(:any)', 'ProfileController::serveImage/$1');

// Test Routes
$routes->get('test', 'Test::index');
$routes->get('test/check', 'Test::check');

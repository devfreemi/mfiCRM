<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->add('backoffice-user-signup', 'Auth::signup_validation');
$routes->add('backoffice-user-login', 'Auth::login_validation');

$routes->group('', ['filter' => 'AuthCheck'], function ($routes) {
    $routes->add('dashboard', 'Home::dashboard');
    $routes->add('employee', 'Home::employee');
    $routes->add('add-employee', 'Employee::add_employee');
    $routes->add('branch', 'Home::branch');
    $routes->add('add-branch', 'BranchDetails::add_branch');
    $routes->add('group', 'Home::group');
    $routes->add('members', 'Home::members');
    $routes->add('loan', 'Home::loan');
    $routes->add('application-view', 'Home::loan_view');
    $routes->add('logout', 'Auth::logout');
});

$routes->group('', ['filter' => 'LoginCheck'], function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('sign-up', 'Auth::signup');
});

// API FOR APP SERVICE
$routes->add('api/login-api-v1', 'Employee::api_login');

$routes->group('', ['filter' => 'AuthFilterJWT'], function ($routes) {
    $routes->add('api/employee-details-api-v1', 'EmployeeDetails::get_employee');
    $routes->add('api/branch-details-api-v1', 'BranchApi::barnch_api');
    $routes->add('api/branch-list-api-v1', 'BranchListAPI::barnch_list_api');
    $routes->add('api/creat-group-api-v1', 'GroupApi::add_group');
    $routes->add('api/group-list-api-v1', 'GroupListApi::group_list_api');
    $routes->add('api/group-details-api-v1', 'GroupDetailsApi::group_details');
    $routes->add('api/add-member-api-v1', 'MemberController::add_member');
    $routes->add('api/view-total-member-api-v1', 'MemberController::view_member');
    $routes->add('api/member-list-api-v1', 'MemberController::member_list_api');
    $routes->add('api/member-view-api-v1', 'MemberController::member_view_api');
    $routes->add('api/member-loan-api-v1', 'LoanApi::applied_loan');
    $routes->add('api/loan-list-api-v1', 'LoanApi::list_of_loan');
    $routes->add('api/loan-details-api-v1', 'LoanApi::details_of_loan');
});
// $routes->add('api/loan-details-api-v1', 'LoanApi::details_of_loan');




$routes->add('mobile-api-v1', 'Home::api_v1_mobile');
$routes->add('mobile-count-api-v1', 'Home::api_v1_mobile_count');
$routes->add('email-count-api-v1', 'Home::api_v1_email_count');
$routes->add('mobile-api-update-v1', 'Home::api_v1_update_mobile');
$routes->add('service-api-v1', 'Home::api_v1_service');
$routes->add('service-api-gst-v1', 'Home::api_v1_service_gst');
$routes->add('service-api-dsc-v1', 'Home::api_v1_service_dsc');
$routes->add('service-api-company-v1', 'Home::api_v1_service_company');
$routes->add('service-api-compliance-v1', 'Home::api_v1_service_compliance');
// License
$routes->add('license-api-gst-v1', 'Home::api_v1_license_gst');
$routes->add('license-api-trade-v1', 'Home::api_v1_license_trade');
$routes->add('registration-api-trademark-v1', 'Home::api_v1_license_trademark');
// Others
$routes->add('dashboard-api-v1', 'Home::api_v1_dashboard');
$routes->add('dashboard-api-service-list-v1', 'Home::api_v1_dashboard_ser_list');
$routes->add('application-api-view-v1', 'Home::api_v1_application_view');
$routes->add('profile-api-v1', 'Home::api_v1_profile');
$routes->add('partner-api-v1', 'Home::api_v1_partner');
$routes->add('image-api-v1', 'Home::api_v1_image');
// Payment
$routes->add('payment-api-v1', 'Home::api_v1_payment');
$routes->add('payment-response-api-v1', 'Home::api_v1_payment_response');

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
    $routes->add('member-edit', 'Home::member_edit');
    $routes->add('loan', 'Home::loan');
    $routes->add('application-view', 'Home::loan_view');
    $routes->add('group-view', 'Home::group_view');
    $routes->add('update-loan', 'LoanApi::update_of_loan');
    $routes->add('logout', 'Auth::logout');
    $routes->add('loan-details-view', 'Home::loan_details');
    $routes->add('bank', 'Home::bank');
    $routes->add('bank-deposite', 'Home::bank_deposite');
    $routes->add('add-bank', 'BankController::add_bank');
    $routes->add('geo-tag', 'Home::geo_tag');
    $routes->add('employee-attendence', 'Home::e_attendence');
    $routes->post('loan/check', 'LoanEligibilityController::checkEligibility');
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
    $routes->add('api/member-total-api-v1', 'MemberController::total_member');
    $routes->add('api/member-loan-api-v1', 'LoanApi::applied_loan');
    $routes->add('api/loan-list-api-v1', 'LoanApi::list_of_loan');
    $routes->add('api/loan-details-api-v1', 'LoanApi::details_of_loan');
    $routes->add('api/total-group-api-v1', 'GroupListApi::total_group');
    $routes->add('api/total-loan-status-count-api-v1', 'LoanApi::status_count_of_loan');
    $routes->add('api/loan-emi-api-v1', 'LoanApi::loan_emi');
    $routes->add('api/loan-emi-payment-status-api-v1', 'LoanApi::loan_emi_payment_status');
    $routes->add('api/loan-disbursement-status', 'LoanApi::disbursement_status');
    $routes->add('api/loan-disbursement-member-status', 'LoanApi::disbursement_status_member');
    $routes->add('api/loan-disbursement-member-details', 'LoanApi::disbursement_details_member');
    $routes->add('api/loan-disbursement-verification', 'LoanApi::disbursement_verification');
    $routes->add('api/loan-collection-status', 'LoanApi::collection_status');
    $routes->add('api/loan-collection-member-details', 'LoanApi::collection_details_member');
    $routes->add('api/loan-collection-update', 'LoanApi::collection_details_submit');
    $routes->add('api/group-total-stats-disbursed', 'LoanApi::total_gr_disbursed');
    $routes->add('api/group-total-stats-outstanding', 'LoanApi::total_gr_outstanding');
    $routes->add('api/bank-list', 'BankController::bank_list_api');
    $routes->add('api/diposite-member-details', 'LoanApi::diposite_details_member');
    $routes->add('api/bank-deposite', 'DepositeController::add_deposite');
    $routes->add('api/today-deposite-data', 'DepositeController::temp_data');
    $routes->add('api/today-deposite-data-total', 'DepositeController::deposite_verification_total');
    $routes->add('api/deposite-verification', 'DepositeMaster::submit_deposite_master');
    $routes->add('api/geo-tag', 'GeoTag::getData');
    $routes->add('api/geo-tag-list', 'GeoTag::listData');
    $routes->add('api/total-outstanding', 'LoanApi::total_outstanding');
    $routes->add('api/total-disbursed', 'LoanApi::total_disbursed');
    $routes->add('api/total-outstanding-month', 'LoanApi::total_outstanding_month');
    $routes->add('api/total-disbursed-month', 'LoanApi::total_disbursed_month');
    // KYC Verification
    $routes->add('api/kyc-aadhaar-send-otp', 'AadhaarKycController::send_otp');
    $routes->add('api/kyc-aadhaar-verify-otp', 'AadhaarKycController::verify_otp');
    $routes->add('api/kyc-pan-verify', 'AadhaarKycController::verify_pan');
    $routes->add('api/gst-verify', 'AadhaarKycController::verify_gst');
    $routes->add('api/check-eli-api-v1', 'LoanEligibilityController::checkEligibilityAPI');
    $routes->add('api/get-eli-loan-api-v1', 'LoanEligibilityController::get_approval');
    $routes->add('api/get-approved-retailer-v1', 'LoanEligibilityController::approved_retailer');
    $routes->add('api/get-approved-retailer-data-v1', 'LoanEligibilityController::approved_retailer_data');
    // KYC Verification End
    $routes->add('api/log-out-api-v1', 'LogOutController::logout_emp');
});
// $routes->add('api/today-deposite-data', 'DepositeController::temp_data');

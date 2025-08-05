<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->add('backoffice-user-signup', 'Auth::signup_validation');
$routes->add('backoffice-user-login', 'Auth::login_validation');

$routes->add('retailers/fi/(:any)', 'MemberController::retailer_fi/$1');
$routes->add('submit-fi', 'FiCheckController::submit');
$routes->get('fi-report', 'FiCheckController::report');
$routes->get('get-place-name', 'GeoController::reverse');
// PDF Generate and eSign
$routes->get('preview-pdf/(:any)', 'GeneratePdf::previewSanctionLetter/$1');

$routes->get('generate-pdf/(:segment)', 'GeneratePdf::generatePdf/$1');
$routes->get('redirection', 'Home::redirection');
$routes->get('digi-success', 'Home::digi_success');

$routes->group('', ['filter' => 'AuthCheck'], function ($routes) {
    $routes->add('dashboard', 'Home::dashboard');
    $routes->add('employee', 'Home::employee');
    $routes->add('add-employee', 'Employee::add_employee');
    $routes->add('branch', 'Home::branch');
    $routes->add('add-branch', 'BranchDetails::add_branch');
    $routes->add('group', 'Home::group');
    $routes->add('members', 'Home::members');
    $routes->add('member-edit', 'Home::member_edit');
    $routes->add('member-add', 'Home::member_add');
    $routes->add('add-member', 'MemberController::add_member_crm');
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
    $routes->add('retailers/details/(:any)', 'MemberController::retailer_profile/$1');

    $routes->add('emi/payment', 'PaymentController::initiate_payment');
    $routes->add('disbursement', 'Home::disbursement');
    $routes->add('disbursement/details/(:any)', 'LoanApi::disbursement_details/$1');
    $routes->get('payment/details', 'PaymentController::details');
    $routes->get('payment/conformation', 'PaymentController::pay_conf');
    $routes->add('loan-create', 'LoanApi::loan_create');
    $routes->add('retailers/cibil-report/(:any)', 'LoanEligibilityController::cibil_report/$1');
    // PFD Test

});

$routes->group('', ['filter' => 'LoginCheck'], function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('sign-up', 'Auth::signup');
});

// API FOR APP SERVICE
$routes->add('api/login-api-v1', 'Employee::api_login');
$routes->add('api/member/login-v1', 'MemberLoginController::member_application_login');
$routes->add('api/member/register-v1', 'MemberLoginController::member_application_register');

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
    $routes->add('api/kyc-aadhaar-check-status', 'AadhaarKycController::digi_status');
    $routes->add('api/kyc-aadhaar-verify-otp', 'AadhaarKycController::verify_otp');
    $routes->add('api/kyc-pan-search', 'AadhaarKycController::get_pan'); // For PAN Search
    $routes->add('api/kyc-pan-verify', 'AadhaarKycController::verify_pan');
    $routes->add('api/gst-verify', 'AadhaarKycController::verify_gst');
    $routes->add('api/voter-id-verify', 'AadhaarKycController::verify_voter_id');
    $routes->add('api/check-eli-api-v1', 'LoanEligibilityController::checkEligibilityAPI');
    $routes->add('api/get-eli-loan-api-v1', 'LoanEligibilityController::get_approval');
    $routes->add('api/get-approved-retailer-v1', 'LoanEligibilityController::approved_retailer');
    $routes->add('api/get-approved-retailer-data-v1', 'LoanEligibilityController::approved_retailer_data');
    $routes->add('api/retailer-doc-upload-v1', 'RetailerDocumentsController::add_doc');
    $routes->add('api/check-shop-image-v1', 'RetailerDocumentsController::check_shop_image_status');
    $routes->add('api/check-pan-voter-v1', 'RetailerDocumentsController::check_pan_voter_status');
    $routes->add('api/check-home-address-proof-v1', 'RetailerDocumentsController::check_home_address_proof_status');
    $routes->add('api/check-shop-address-proof-v1', 'RetailerDocumentsController::check_shop_address_proof_status');
    $routes->add('api/check-business-proof-v1', 'RetailerDocumentsController::check_business_docs_status');
    $routes->add('api/check-bank-statement-v1', 'RetailerDocumentsController::check_bank_statement_status');
    $routes->add('api/bank-statement-analyze-v1', 'RetailerDocumentsController::check_bank_statement_analyze');
    $routes->add('api/check-all-doc-v1', 'RetailerDocumentsController::checkUploadedDocs');
    // KYC Verification End
    $routes->add('api/log-out-api-v1', 'LogOutController::logout_emp');
    // Collection in LOS
    $routes->add('api/los/emi/get-today-emi-v1', 'PaymentController::get_today_emi');
    $routes->add('api/los/order/create-emi-order-v1', 'PaymentController::generate_order');
    // Field Investigation
    $routes->add('api/fi/start', 'FIautoController::auto_mail_fi_start');
    $routes->add('api/verify-bank-v1', 'BankController::bank_verification_rm_app');
    $routes->add('api/analyze-image-v1', 'RetailerDocumentsController::analyzeImage');
    $routes->add('api/analyze-purchase-image-v1', 'RetailerDocumentsController::analyzeImagePurchase');
});
// $routes->add('api/fi/start', 'FIautoController::auto_mail_fi_start');
$routes->add('api/page/verify-bank-v1', 'BankController::bank_verification');

$routes->group(
    '',
    ['filter' => 'MemberAuthFilter'],
    function ($routes) {
        $routes->add('api/member/add-member-api-v1', 'MemberController::add_member');
        $routes->add('api/member/member-details-v1', 'MemberLoginController::member_details');
        $routes->add('api/member/member-update-v1', 'MemberController::update_member');
        $routes->add('api/member/kyc/pan-validation-v1', 'AadhaarKycController::verify_pan_user');
        $routes->add('api/member/gst/gst-validation-v1', 'AadhaarKycController::verify_gst_user');
        $routes->add('api/member/emi/get-today-emi-v1', 'PaymentController::get_today_emi');
        $routes->add('api/member/order/create-emi-order-v1', 'PaymentController::generate_order');
        $routes->add('api/member/payment/payment-conformation-v1', 'PaymentController::conformation');
        $routes->add('api/loan-list-api-v1', 'LoanApi::list_of_loan');
        $routes->add('api/loan-details-api-v1', 'LoanApi::details_of_loan');
        // $routes->add('api/loan-details-api-v1', 'LoanApi::details_of_loan');
        $routes->add('api/retailer-loan-doc-api-v1', 'MemberController::retailer_loan_doc');
    }
);
$routes->add('payment/redirect/gateway', 'PaymentController::app_payment_collection');
$routes->get('payment/conformation/gateway', 'PaymentController::app_pay_conf');

// Test API
// $routes->add('api/member/gst/gst-validation-v1', 'AadhaarKycController::verify_gst_user');
// $routes->add('api/member/order/create-emi-order-v1', 'PaymentController::generate_order');
$routes->get('member/kyc', 'AadhaarKycController::kyc');
$routes->add('page/kyc-aadhaar-send-otp', 'AadhaarKycController::send_otp_page');
$routes->add('page/kyc-aadhaar-verify-otp', 'AadhaarKycController::verify_otp_page');

$routes->add('api/protean', 'ApiController::index');
$routes->add('api/protean/decrypt', 'ProteanDataDecryptController::index');
$routes->add('api/ip/check', 'IpCheckController::index');

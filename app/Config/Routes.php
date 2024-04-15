<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->add('backoffice-user-login', 'Home::user_login');
$routes->add('dashboard', 'Home::panel');
$routes->add('customer/(:any)', 'Home::panel');
$routes->add('customer-list/', 'Home::panel');
$routes->add('insert-data', 'Home::insert_data');
$routes->add('terms-condition', 'Home::terms');
// API FOR APP SERVICE
$routes->add('login-api-v1', 'Home::api_v1_login');
$routes->add('mobile-login-api-v1', 'Home::api_v1_login_mobile');
$routes->add('mobile-api-v1', 'Home::api_v1_mobile');
$routes->add('mobile-count-api-v1', 'Home::api_v1_mobile_count');
$routes->add('mobile-api-update-v1', 'Home::api_v1_update_mobile');
$routes->add('service-api-v1', 'Home::api_v1_service');
$routes->add('service-api-gst-v1', 'Home::api_v1_service_gst');
$routes->add('service-api-dsc-v1', 'Home::api_v1_service_dsc');
$routes->add('service-api-company-v1', 'Home::api_v1_service_company');
$routes->add('service-api-compliance-v1', 'Home::api_v1_service_compliance');
// License
$routes->add('license-api-gst-v1', 'Home::api_v1_license_gst');
$routes->add('license-api-trade-v1', 'Home::api_v1_license_trade');
$routes->add('license-api-pan-v1', 'Home::api_v1_application_pan');
// Others
$routes->add('dashboard-api-v1', 'Home::api_v1_dashboard');
$routes->add('dashboard-api-service-list-v1', 'Home::api_v1_dashboard_ser_list');
$routes->add('profile-api-v1', 'Home::api_v1_profile');
// Payment
$routes->add('payment-api-v1', 'Home::api_v1_payment');
$routes->add('payment-response-api-v1', 'Home::api_v1_payment_response');

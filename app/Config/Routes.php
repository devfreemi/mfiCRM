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
// API FOR APP
$routes->add('login-api-v1', 'Home::api_v1_login');
$routes->add('mobile-api-v1', 'Home::api_v1_mobile');
$routes->add('mobile-api-update-v1', 'Home::api_v1_update_mobile');
$routes->add('service-api-v1', 'Home::api_v1_service');
$routes->add('service-api-gst-v1', 'Home::api_v1_service_gst');
$routes->add('dashboard-api-v1', 'Home::api_v1_dashboard');
$routes->add('dashboard-api-service-list-v1', 'Home::api_v1_dashboard_ser_list');
$routes->add('profile-api-v1', 'Home::api_v1_profile');

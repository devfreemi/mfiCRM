<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->add('backoffice-user-login', 'Home::user_login');
$routes->add('dashboard', 'Home::panel');
$routes->add('customer/(:any)', 'Home::panel');
// API FOR APP
$routes->add('login-api-v1', 'Home::api_v1_login');
$routes->add('service-api-v1', 'Home::api_v1_service');

<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->group('admin', function ($routes) {
    $routes->group('auth', ['namespace' => 'App\Controllers\Api\Admin\Auth'], function ($routes) {
        $routes->post('login', 'AdminAuthController::login');
        $routes->post('register', 'AdminAuthController::register');
        $routes->get('refresh', 'AdminAuthController::refresh', ['filter' => 'authRefreshToken']);
    });

    $routes->resource('parents', ['namespace' => 'App\Controllers\Api\Admin\parents', 'filter' => 'authToken', 'controller' => 'ParentsController', 'except' => ['new']]);

    $routes->resource('profile', ['namespace' => 'App\Controllers\Api\Admin\profile', 'filter' => 'authToken', 'controller' => 'ProfileController', 'except' => ['new']]);
});
$routes->group('teacher', function ($routes) {
    $routes->group('auth', ['namespace' => 'App\Controllers\Api\Teacher\Auth'], function ($routes) {
        $routes->post('login', 'TeacherAuthController::login');
        $routes->post('register', 'TeacherAuthController::register');
        $routes->get('refresh', 'TeacherAuthController::refresh', ['filter' => 'authRefreshToken']);
    });
});

// $routes->post('/login', 'Auth::login');
// $routes->resource('classroom', [
//     'filter'     => 'authToken',
//     'controller' => 'Classroom'
// ]);

// $routes->resource('usercontroller', [
//     'filter'     => 'authToken',
//     'controller' => 'UserController',
// ]);




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

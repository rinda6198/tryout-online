<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============ PUBLIC / AUTH ROUTES ============
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::login', ['filter' => 'guest']);
$routes->post('/login', 'Auth::attemptLogin', ['filter' => 'guest']);
$routes->get('/register', 'Auth::register', ['filter' => 'guest']);
$routes->post('/register', 'Auth::attemptRegister', ['filter' => 'guest']);
$routes->get('/logout', 'Auth::logout');

// ============ DASHBOARD (role-based redirect) ============
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// ============ ADMIN ROUTES ============
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'role:admin'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Users CRUD
    $routes->get('users', 'UserController::index');
    $routes->get('users/create', 'UserController::create');
    $routes->post('users/store', 'UserController::store');
    $routes->get('users/edit/(:num)', 'UserController::edit/$1');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->get('users/delete/(:num)', 'UserController::delete/$1');

    // Subjects CRUD
    $routes->get('subjects', 'SubjectController::index');
    $routes->post('subjects/store', 'SubjectController::store');
    $routes->post('subjects/update/(:num)', 'SubjectController::update/$1');
    $routes->get('subjects/delete/(:num)', 'SubjectController::delete/$1');

    // =========================
// ADMIN PAKAI MODUL GURU
// =========================

    $routes->get(
        'packages',
        '\App\Controllers\Guru\PackageController::index'
    );

    $routes->get(
        'packages/create',
        '\App\Controllers\Guru\PackageController::create'
    );

    $routes->post(
        'packages/store',
        '\App\Controllers\Guru\PackageController::store'
    );

    $routes->get(
        'packages/edit/(:num)',
        '\App\Controllers\Guru\PackageController::edit/$1'
    );

    $routes->post(
        'packages/update/(:num)',
        '\App\Controllers\Guru\PackageController::update/$1'
    );

    $routes->get(
        'packages/delete/(:num)',
        '\App\Controllers\Guru\PackageController::delete/$1'
    );

    // SOAL

    $routes->get(
        'packages/(:num)/questions',
        '\App\Controllers\Guru\QuestionController::index/$1'
    );

    $routes->get(
        'packages/(:num)/questions/create',
        '\App\Controllers\Guru\QuestionController::create/$1'
    );

    $routes->post(
        'packages/(:num)/questions/store',
        '\App\Controllers\Guru\QuestionController::store/$1'
    );

    $routes->get(
        'questions/edit/(:num)',
        '\App\Controllers\Guru\QuestionController::edit/$1'
    );

    $routes->post(
        'questions/update/(:num)',
        '\App\Controllers\Guru\QuestionController::update/$1'
    );

    $routes->get(
        'questions/delete/(:num)',
        '\App\Controllers\Guru\QuestionController::delete/$1'
    );

    // IMPORT

    $routes->get(
        'packages/(:num)/import',
        '\App\Controllers\Guru\QuestionController::importForm/$1'
    );

    $routes->post(
        'packages/(:num)/import',
        '\App\Controllers\Guru\QuestionController::import/$1'
    );

    // HASIL

    $routes->get(
        'results',
        '\App\Controllers\Guru\ResultController::index'
    );

    $routes->get(
        'results/package/(:num)',
        '\App\Controllers\Guru\ResultController::byPackage/$1'
    );

    $routes->get(
        'analysis/(:num)',
        '\App\Controllers\Guru\ResultController::analysis/$1'
    );

    // Announcements
    $routes->get('announcements', 'AnnouncementController::index');
    $routes->post('announcements/store', 'AnnouncementController::store');
    $routes->post('announcements/update/(:num)', 'AnnouncementController::update/$1');
    $routes->get('announcements/delete/(:num)', 'AnnouncementController::delete/$1');
});

// ============ GURU ROUTES ============
$routes->group('guru', ['namespace' => 'App\Controllers\Guru', 'filter' => 'role:guru,admin'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Packages CRUD
    $routes->get('packages', 'PackageController::index');
    $routes->get('packages/create', 'PackageController::create');
    $routes->post('packages/store', 'PackageController::store');
    $routes->get('packages/edit/(:num)', 'PackageController::edit/$1');
    $routes->post('packages/update/(:num)', 'PackageController::update/$1');
    $routes->get('packages/delete/(:num)', 'PackageController::delete/$1');

    // Questions CRUD
    $routes->get('packages/(:num)/questions', 'QuestionController::index/$1');
    $routes->get('packages/(:num)/questions/create', 'QuestionController::create/$1');
    $routes->post('packages/(:num)/questions/store', 'QuestionController::store/$1');
    $routes->get('questions/edit/(:num)', 'QuestionController::edit/$1');
    $routes->post('questions/update/(:num)', 'QuestionController::update/$1');
    $routes->get('questions/delete/(:num)', 'QuestionController::delete/$1');

    $routes->get('packages/(:num)/import', 'QuestionController::importForm/$1');
    $routes->post('packages/(:num)/import', 'QuestionController::import/$1');

    // Results & analysis
    $routes->get('results', 'ResultController::index');
    $routes->get('results/package/(:num)', 'ResultController::byPackage/$1');
    $routes->get('analysis/(:num)', 'ResultController::analysis/$1');
});

// ============ PESERTA ROUTES ============
$routes->group('peserta', ['namespace' => 'App\Controllers\Peserta', 'filter' => 'role:peserta'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Try out
    $routes->get('tryout', 'TryoutController::index');
    $routes->get('tryout/token/(:num)', 'TryoutController::tokenForm/$1');
    $routes->post('tryout/start/(:num)', 'TryoutController::start/$1');
    $routes->get('tryout/exam/(:num)', 'TryoutController::exam/$1');
    $routes->get('tryout/exam-auto-finish/(:num)', 'TryoutController::autoFinish/$1');
    $routes->post('tryout/save-answer', 'TryoutController::saveAnswer');
    $routes->post('tryout/finish/(:num)', 'TryoutController::finish/$1');
    $routes->get('tryout/result/(:num)', 'TryoutController::result/$1');

    // History & ranking
    $routes->get('history', 'HistoryController::index');
    $routes->get('ranking/(:num)', 'HistoryController::ranking/$1');
});

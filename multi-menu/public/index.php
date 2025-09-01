<?php
// FRONT CONTROLLER
require __DIR__ . '/../app/config/app.php';       // define config() e base_url() via Helpers
require __DIR__ . '/../app/core/Helpers.php';
require __DIR__ . '/../app/core/Router.php';
require __DIR__ . '/../app/core/Controller.php';

// Rotas
$router = new Router();

/* Públicas */
$router->get('/{slug}', 'PublicHomeController@index');

/* Admin */
$router->get('/admin/{slug}/login', 'AdminAuthController@loginForm');
$router->post('/admin/{slug}/login', 'AdminAuthController@login');
$router->get('/admin/{slug}/logout', 'AdminAuthController@logout');
$router->get('/admin/{slug}/dashboard', 'AdminDashboardController@index');

// despacha
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

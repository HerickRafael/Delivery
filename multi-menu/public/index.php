<?php
require __DIR__ . '/../app/config/app.php';
require __DIR__ . '/../app/core/Helpers.php';
require __DIR__ . '/../app/core/Router.php';
require __DIR__ . '/../app/core/Controller.php';

$router = new Router();

/* Rotas públicas (cardápio) */
$router->get('/{slug}', 'PublicHomeController@index');

/* Rotas admin por empresa */
$router->get('/admin/{slug}/login', 'AdminAuthController@loginForm');
$router->post('/admin/{slug}/login', 'AdminAuthController@login');
$router->get('/admin/{slug}/dashboard', 'AdminDashboardController@index');
$router->get('/admin/{slug}/logout', 'AdminAuthController@logout');

// ADMIN – Configurações gerais
$router->get('/admin/{slug}/settings',        'AdminSettingsController@index');
$router->post('/admin/{slug}/settings',       'AdminSettingsController@save');

/* >>> Admin: Pedidos (com slug) <<< */
$router->get('/admin/{slug}/orders',              'AdminOrdersController@index');
$router->get('/admin/{slug}/orders/show',         'AdminOrdersController@show');
$router->post('/admin/{slug}/orders/setStatus',   'AdminOrdersController@setStatus');

// BUSCA no público
$router->get('/{slug}/buscar', 'PublicHomeController@index');

// ADMIN – Categorias (CRUD)
$router->get('/admin/{slug}/categories',            'AdminCategoryController@index');
$router->get('/admin/{slug}/categories/create',     'AdminCategoryController@create');
$router->post('/admin/{slug}/categories',           'AdminCategoryController@store');
$router->get('/admin/{slug}/categories/{id}/edit',  'AdminCategoryController@edit');
$router->post('/admin/{slug}/categories/{id}',      'AdminCategoryController@update');
$router->post('/admin/{slug}/categories/{id}/del',  'AdminCategoryController@destroy');

// ADMIN – Produtos (CRUD)
$router->get('/admin/{slug}/products',              'AdminProductController@index');
$router->get('/admin/{slug}/products/create',       'AdminProductController@create');
$router->post('/admin/{slug}/products',             'AdminProductController@store');
$router->get('/admin/{slug}/products/{id}/edit',    'AdminProductController@edit');
$router->post('/admin/{slug}/products/{id}',        'AdminProductController@update');
$router->post('/admin/{slug}/products/{id}/del',    'AdminProductController@destroy');


/* Normaliza a URI removendo o base path (/multi-menu/public) */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(parse_url(config('base_url'), PHP_URL_PATH), '/'); // deve ser /multi-menu/public
if (strpos($uri, $basePath) === 0) {
  $uri = substr($uri, strlen($basePath));
}
if ($uri === '' || $uri === false) $uri = '/';

/* Despacha */
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

<?php

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use tdt4237\webapp\Auth;
use tdt4237\webapp\Hash;
use tdt4237\webapp\repository\MovieRepository;
use tdt4237\webapp\repository\MovieReviewRepository;
use tdt4237\webapp\repository\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';

chdir(__DIR__ . '/../');
chmod(__DIR__ . '/../web/uploads', 0700);

$app = new Slim([
    'templates.path' => __DIR__.'/webapp/templates/',
    'debug' => true,
    'view' => new Twig()
]);

$view = $app->view();
$view->parserExtensions = array(
    new TwigExtension(),
);

try {
    // Create (connect to) SQLite database in file
    $app->db = new PDO('sqlite:app.db');
    // Set errormode to exceptions
    $app->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit();
}

// Wire together dependencies

$app->hash = new Hash();
$app->userRepository = new UserRepository($app->db);
$app->movieRepository = new MovieRepository($app->db);
$app->movieReviewRepository = new MovieReviewRepository($app->db);
$app->auth = new Auth($app->userRepository, $app->hash);

$ns ='tdt4237\\webapp\\controllers\\';

// Home page at http://localhost:8080/
$app->get('/', $ns . 'IndexController:index');

// Login form
$app->get('/login', $ns . 'LoginController:index');
$app->post('/login', $ns . 'LoginController:login');

// New user
$app->get('/user/new', $ns . 'UserController:index')->name('newuser');
$app->post('/user/new', $ns . 'UserController:create');

// Edit logged in user
$app->get('/user/edit', $ns . 'UserController:showUserEditForm')->name('editprofile');
$app->post('/user/edit', $ns . 'UserController:receiveUserEditForm');

// Show a user by name
$app->get('/user/:username', $ns . 'UserController:show')->name('showuser');

// Show all users
$app->get('/users', $ns . 'UserController:all');

// Log out
$app->get('/logout', $ns . 'UserController:logout')->name('logout');

// Admin restricted area
$app->get('/admin', $ns . 'AdminController:index')->name('admin');
$app->get('/admin/delete/:username', $ns . 'AdminController:delete');

// Movies
$app->get('/movies', $ns . 'MovieController:index')->name('movies');
$app->get('/movies/:movieid', $ns . 'MovieController:show');
$app->post('/movies/:movieid', $ns . 'MovieController:addReview');

return $app;

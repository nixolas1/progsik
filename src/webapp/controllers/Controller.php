<?php

namespace tdt4237\webapp\controllers;

class Controller
{
    protected $app;
    
    protected $userRepository;
    protected $auth;
    protected $postRepository;

    public function __construct()
    {
        $this->app = \Slim\Slim::getInstance();
        $this->userRepository = $this->app->userRepository;
        $this->postRepository = $this->app->postRepository;
        $this->postRepository = $this->app->postRepository;
        $this->commentRepository = $this->app->commentRepository;
        $this->auth = $this->app->auth;
        $this->csrf = $this->app->csrf;
        $this->hash = $this->app->hash;
    }

    protected function render($template, $variables = [])
    {
        $variables['csrfToken'] = $this->csrf->getToken();

        if ($this->auth->check()) {
            $variables['isLoggedIn'] = true;
            $variables['isAdmin'] = $this->auth->isAdmin();
            $variables['loggedInUsername'] = $_SESSION['user'];
        }

        print $this->app->render($template, $variables);
    }
}

<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\repository\UserRepository;

class LoginController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->auth->check()) {
            $username = $this->auth->user()->getUsername();
            $this->app->flash('info', 'You are already logged in as ' . $username);
            $this->app->redirect('/');
            return;
        }

        $this->render('login.twig', []);
    }

    public function login()
    {
        $request = $this->app->request;
        $user    = $request->post('user');
        $pass    = $request->post('pass');

        if ($this->auth->checkCredentials($user, $pass)) {
            $_SESSION['user'] = $user;
            $_SESSION['token'] = md5(uniqid(mt_rand(), true));
            $this->app->flash('info', "You are now successfully logged in as $user.");
            $this->app->redirect('/');
        }
        
        $this->app->flashNow('error', 'Incorrect user/pass combination.');
        $this->render('login.twig', []);
    }
}

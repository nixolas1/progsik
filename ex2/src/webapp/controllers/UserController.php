<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Age;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\User;
use tdt4237\webapp\validation\EditUserFormValidation;
use tdt4237\webapp\validation\RegistrationFormValidation;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->auth->guest()) {
            return $this->render('newUserForm.twig', []);
        }

        $username = $this->auth->user()->getUserName();
        $this->app->flash('info', 'You are already logged in as ' . $username);
        $this->app->redirect('/');
    }

    public function create()
    {
        $request  = $this->app->request;
        $username = $request->post('user');
        $password = $request->post('pass');

        $validation = new RegistrationFormValidation($username, $password);

        if ($validation->isGoodToGo()) {
            $user = new User($username, $this->hash->make($password));
            $this->userRepository->save($user);

            $this->app->flash('info', 'Thanks for creating a user. Now log in.');
            return $this->app->redirect('/login');
        }

        $errors = join("<br>\n", $validation->getValidationErrors());
        $this->app->flashNow('error', $errors);
        $this->render('newUserForm.twig', ['username' => $username]);
    }

    public function all()
    {
        $this->render('users.twig', [
            'users' => $this->userRepository->all()
        ]);
    }

    public function logout()
    {
        $this->auth->logout();
        $this->app->redirect('/?msg=Successfully logged out.');
    }

    public function show($username)
    {
        $user = $this->userRepository->findByUser($username);

        $this->render('showuser.twig', [
            'user'     => $user,
            'username' => $username
        ]);
    }

    public function showUserEditForm()
    {
        $this->makeSureUserIsAuthenticated();

        $this->render('edituser.twig', [
            'user' => $this->auth->user()
        ]);
    }

    public function receiveUserEditForm()
    {
        $this->makeSureUserIsAuthenticated();
        $user = $this->auth->user();

        $request = $this->app->request;
        $email   = $request->post('email');
        $bio     = $request->post('bio');
        $age     = $request->post('age');

        $validation = new EditUserFormValidation($email, $bio, $age);

        if ($validation->isGoodToGo()) {
            $user->setEmail(new Email($email));
            $user->setBio($bio);
            $user->setAge(new Age($age));

            $this->userRepository->save($user);

            $this->app->flashNow('info', 'Your profile was successfully saved.');
            return $this->render('edituser.twig', ['user' => $user]);
        }

        $this->app->flashNow('error', join('<br>', $validation->getValidationErrors()));
        $this->render('edituser.twig', ['user' => $user]);
    }

    public function makeSureUserIsAuthenticated()
    {
        if ($this->auth->guest()) {
            $this->app->flash('info', 'You must be logged in to edit your profile.');
            $this->app->redirect('/login');
        }
    }
}

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
        $fullname = $request->post('fullname');
        $address = $request->post('address');
        $postcode = $request->post('postcode');


        $validation = new RegistrationFormValidation($username, $password, $fullname, $address, $postcode);

        if ($validation->isGoodToGo()) {
            $password = $password;
            $password = $this->hash->make($password);
            $user = new User($username, $password, $fullname, $address, $postcode);
            $this->userRepository->save($user);

            $this->app->flash('info', 'Thanks for creating a user. Now log in.');
            return $this->app->redirect('/login');
        }

        $errors = $validation->getValidationErrors();
        $this->app->flashNow('error', $errors);
        $this->render('newUserForm.twig', ['username' => $username]);
    }


    public function logout()
    {
        $this->auth->logout();
        $this->app->redirect('/');
    }

    public function show($username)
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        } else {
            $user = $this->userRepository->findByUser($username);
            if($user != false){
                if ($this->auth->isAdmin() || $user->getUsername() == $this->auth->getUsername()) {

                    if($this->auth->isDoctor()){
                        $user->setEarned($this->userRepository->getEarned($username));
                    }
                    if($this->auth->isPaying()){
                        $user->setSpent($this->userRepository->getSpent($username));
                    }
                    if($this->auth->isAdmin()){
                        $user->setCompanyEarned($this->userRepository->getCompanyEarned());
                    }

                    $this->render('showuser.twig', [
                        'user' => $user,
                        'username' => $username
                    ]);
                } else if ($this->auth->check()) {

                    $this->render('showuserlite.twig', [
                        'user' => $user,
                        'username' => $username
                    ]);
                }
            }else{
                $this->app->flash("info", "Invalid user");
                $this->app->redirect("/");
            }
        }
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
        $fullname = $request->post('fullname');
        $address = $request->post('address');
        $postcode = $request->post('postcode');
        $banknumber = $request->post('banknumber');

        $validation = new EditUserFormValidation($email, $bio, $age, $banknumber);

        if ($validation->isGoodToGo()) {
            $user->setEmail(new Email($email));
            $user->setBio($bio);
            $user->setAge(new Age($age));
            $user->setFullname($fullname);
            $user->setAddress($address);
            $user->setPostcode($postcode);
            $user->setBanknumber($banknumber);
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

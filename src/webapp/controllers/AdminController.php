<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\Auth;
use tdt4237\webapp\models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->checkAdmin();

        $variables = [
            'users' => $this->userRepository->all(),
            'posts' => $this->postRepository->all()
        ];
        $this->render('admin.twig', $variables);
    }

    public function delete($username)
    {
        $this->checkAdmin();

        if ($this->userRepository->deleteByUsername($username) === 1) {
            $this->app->flash('info', "Sucessfully deleted '$username'");
            $this->app->redirect('/admin');
            return;
        }
        
        $this->app->flash('info', "An error ocurred. Unable to delete user '$username'.");
        $this->app->redirect('/admin');
    }

    public function setDoc($username)
    {
        $this->checkAdmin();

        if ($this->userRepository->setDocByUsername($username) === 1) {
            $this->app->flash('info', "Sucessfully set '$username' as a Doctor");
            $this->app->redirect('/admin');
            return;
        }
        
        $this->app->flash('info', "An error ocurred. Unable to set '$username' to Doctor.");
        $this->app->redirect('/admin');
    }

    public function removeDoc($username)
    {
        $this->checkAdmin();

        if ($this->userRepository->removeDocByUsername($username) === 1) {
            $this->app->flash('info', "Sucessfully removed '$username' as a Doctor");
            $this->app->redirect('/admin');
            return;
        }
        
        $this->app->flash('info', "An error ocurred. Unable to remove '$username' from Doctor.");
        $this->app->redirect('/admin');
    }

    public function deletePost($postId)
    {
        $this->checkAdmin();

        if ($this->postRepository->deleteByPostid($postId) === 1) {
            $this->app->flash('info', "Sucessfully deleted '$postId'");
            $this->app->redirect('/admin');
            return;
        }

        $this->app->flash('info', "An error ocurred. Unable to delete post '$postId'.");
        $this->app->redirect('/admin');
    }

    private function checkAdmin()
    {
        if ($this->auth->guest()) {
            $this->app->flash('info', "You must be logged in to view the admin page.");
            $this->app->redirect('/');
            return;
        }

        if (! $this->auth->isAdmin()) {
            $this->app->flash('info', "You must be administrator to view the admin page.");
            $this->app->redirect('/');
            return;
        }
    }
}

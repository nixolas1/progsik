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
        if ($this->auth->guest()) {
            $this->app->flash('info', "You must be logged in to view the admin page.");
            $this->app->redirect('/');
        }

        if (! $this->auth->isAdmin()) {
            $this->app->flash('info', "You must be administrator to view the admin page.");
            $this->app->redirect('/');
        }

        $variables = [
            'users' => $this->userRepository->all(),
            'posts' => $this->postRepository->allPosts()
        ];
        $this->render('admin.twig', $variables);
    }

    public function delete($username)
    {

        if ($this->auth->isAdmin()) {
            if ($this->userRepository->deleteByUsername($username)) {
                $this->app->flash('info', "Sucessfully deleted '$username'");
                $this->app->redirect('/admin');
            } else {
                $this->app->flash('info', "An error ocurred. Unable to delete user '$username'.");
                $this->app->redirect('/admin');
            }
        } else {
            $this->app->flash('info', "You must be administrator to view the admin page.");
            $this->app->redirect('/');
        }
    }

    public function make_doctor($username, $isdoctor)
    {

        if ($this->auth->isAdmin() && ($isdoctor == "1" || $isdoctor == "0")) {
            $ret = $this->userRepository->setIsDoctorByUsername($username, $isdoctor);
            if ($ret === 1) {
                $this->app->flash('info', "Sucessfully set $username's doctor status to ".$isdoctor);
                $this->app->redirect('/admin');
            } else {
                $this->app->flash('info', "An error ocurred. Unable to set change '$username' as doctor ".$isdoctor);
                $this->app->redirect('/admin');
            }
        } else {
            $this->app->flash('info', "You must be administrator to view the admin page.");
            $this->app->redirect('/');
        }
    }

    public function deletePost($postId)
    {
        if ($this->auth->isAdmin()) {
            if ($this->postRepository->deleteByPostid($postId)) {
                $this->app->flash('info', "Sucessfully deleted post with id '$postId'");
                $this->app->redirect('/admin');
            } else {
                $this->app->flash('info', "An error ocurred. Unable to delete post '$postId'.");
                $this->app->redirect('/admin');
            }
        } else {
            $this->app->flash('info', "You must be administrator to view the admin page.");
            $this->app->redirect('/');
        }
    }
}

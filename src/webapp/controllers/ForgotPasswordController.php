<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 30.08.2015
 * Time: 00:07
 */

namespace tdt4237\webapp\controllers;


class ForgotPasswordController extends Controller {

    public function __construct() {
        parent::__construct();
    }


    function forgotPassword() {
        $this->render('forgotPassword.twig', []);
    }

    function submitName() {

        $username = $this->app->request->post('username');
        $csrf    = $this->app->request->post('csrf_token');

        if (!$this->csrf->validate($csrf)) {
            $this->app->flashNow('error', 'An error occurred with your request.');
            return $this->render('forgotPassword.twig');
        }

        if($username != "") {
            $this->app->flash('success', 'Instructions to reset you password has been sent to your email.');
            // $sendmail
            $this->app->redirect('/login');
        }
        else {
            $this->render('forgotPassword.twig');
            $this->app->flash("error", "Please input a username");
        }

    }

    function deny() {

    }





} 
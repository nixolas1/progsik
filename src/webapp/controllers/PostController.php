<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Post;
use tdt4237\webapp\controllers\UserController;
use tdt4237\webapp\models\Comment;
use tdt4237\webapp\validation\PostValidation;

class PostController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged on to show all posts");
            $this->app->redirect("/login");
        }

        $posts = $this->postRepository->all();

        $posts->sortByDate();

        $Doc = $this->auth->isDoc();

        $this->render('posts.twig', ['posts' => $posts, 'Doc' => $Doc]);
    }

    public function show($postId)
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged on to show a post");
            $this->app->redirect("/login");
        }

        $post = $this->postRepository->find($postId);
        $comments = $this->commentRepository->findByPostId($postId);
        $request = $this->app->request;
        $message = $request->get('msg');
        $variables = [];
        $doc = $this->auth->isDoc();

        if($message) {
            $variables['msg'] = $message;

        }

        $this->render('showpost.twig', [
            'post' => $post,
            'comments' => $comments,
            'flash' => $variables,
            'Doc' => $doc
        ]);

    }

    public function addComment($postId)
    {

        if(!$this->auth->guest()) {

            $comment = new Comment();
            $comment->setAuthor($_SESSION['user']);
            $comment->setText($this->app->request->post("text"));
            $comment->setDate(date("dmY"));
            $comment->setPost($postId);
            $comment->setAnsDoc($this->app->request->post('ansdoc'));

            $this->commentRepository->save($comment);
            $this->app->redirect('/posts/' . $postId);
        }
        else {
            $this->app->redirect('/login');
            $this->app->flash('info', 'you must log in to do that');
        }

    }

    public function showNewPostForm()
    {
        $paid = $this->auth->hasPaid();

        if ($this->auth->check()) {
            $this->render('createpost.twig',[
            'hasPaid' => $paid
        ]);
        } else {

            $this->app->flash('error', "You need to be logged in to create a post");
            $this->app->redirect("/");
        }

    }

    public function create()
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged on to create a post");
            $this->app->redirect("/login");
        } else {
            $request = $this->app->request;
            $title = $request->post('title');
            $content = $request->post('content');
            $author = $_SESSION['user'];
            $date = date("dmY");
            $paydoc = $request->post('paydoc');
            $price = -10;

            $validation = new PostValidation($title, $author, $content, $paydoc);
            if ($validation->isGoodToGo()) {
                $post = new Post();
                $post->setAuthor($author);
                $post->setTitle($title);
                $post->setContent($content);
                $post->setDate($date);
                $post->setPayDoc($paydoc);
                if ($paydoc != 0) {
                    $this->userRepository->updateBalance($author, $price);
                }
                $savedPost = $this->postRepository->save($post);
                $this->app->redirect('/posts/' . $savedPost . '?msg="Post succesfully posted');
            }
        }

            $this->app->flashNow('error', join('<br>', $validation->getValidationErrors()));


            $this->app->render('createpost.twig');

    }
}


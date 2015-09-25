<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Movie;
use tdt4237\webapp\models\MovieReview;

class MovieController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $movies = $this->movieRepository->all();
        $movies->sortByTitle();

        $this->render('movies.twig', ['movies' => $movies]);
    }

    public function show($movieId)
    {
        $this->render('showmovie.twig', [
            'movie' => $this->movieRepository->find($movieId),
            'reviews' => $this->movieReviewRepository->findByMovieId($movieId)
        ]);
    }

    public function addReview($movieId)
    {
        $author = $this->app->request->post('author');
        $text = $this->app->request->post('text');

        $movieReview = new MovieReview();
        
        $movieReview
            ->setAuthor($author)
            ->setText($text)
            ->setMovieId($movieId);

        $this->movieReviewRepository->save($movieReview);

        $this->app->flash('info', 'The review was successfully saved.');
        $this->app->redirect('/movies/' . $movieId);
    }
}

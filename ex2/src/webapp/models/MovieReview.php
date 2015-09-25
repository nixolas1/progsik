<?php

namespace tdt4237\webapp\models;

class MovieReview
{

    private $id = null;
    private $movieId;
    private $author;
    private $text;

    public function getId()
    {
        return $this->id;
    }

    public function getMovieId()
    {
        return $this->movieId;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setMovieId($movieId)
    {
        $this->movieId = $movieId;
        return $this;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}

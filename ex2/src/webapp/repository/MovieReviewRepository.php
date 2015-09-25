<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\MovieReview;

class MovieReviewRepository
{

    /**
     * @var PDO
     */
    private $db;

    const SELECT_BY_ID = "SELECT * FROM moviereviews WHERE id = %s";

    public function __construct(PDO $db)
    {

        $this->db = $db;
    }

    public function save(MovieReview $movieReview)
    {
        $movieId = $movieReview->getMovieId();
        $author  = $movieReview->getAuthor();
        $text    = $movieReview->getText();

        if ($movieReview->getId() === null) {
            $query = "INSERT INTO moviereviews (movieid, author, text) "
                . "VALUES ('$movieId', '$author', '$text')";
        }

        return $this->db->exec($query);
    }

    public function findByMovieId($movieId)
    {
        $query   = "SELECT * FROM moviereviews WHERE movieid = $movieId";
        $rows = $this->db->query($query)->fetchAll();

        return array_map([$this, 'makeFromRow'], $rows);
    }

    public function makeFromRow($row)
    {
        $movieReview = new MovieReview;
        
        return $movieReview
            ->setId($row['id'])
            ->setAuthor($row['author'])
            ->setText($row['text']);
    }
}

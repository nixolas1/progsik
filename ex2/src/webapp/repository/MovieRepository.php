<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Movie;
use tdt4237\webapp\models\MovieCollection;

class MovieRepository
{

    /**
     * @var PDO
     */
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    public static function create($id, $name, $imageUrl)
    {
        $movie = new Movie;
        
        return $movie
            ->setId($id)
            ->setName($name)
            ->setImageUrl($imageUrl);
    }

    public function find($movieId)
    {
        $sql  = "SELECT * FROM movies WHERE id = $movieId";
        $result = $this->db->query($sql);

        return $this->makeFromRow($result->fetch());
    }

    public function all()
    {
        $sql   = "SELECT * FROM movies";
        $results = $this->db->query($sql);

        return new MovieCollection(
            array_map([$this, 'makeFromRow'], $results->fetchAll())
        );
    }

    public function makeFromRow($row)
    {
        return static::create(
            $row['id'],
            $row['name'],
            $row['imageurl']
        );
    }
}

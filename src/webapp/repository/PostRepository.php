<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Post;
use tdt4237\webapp\models\PostCollection;

class PostRepository
{

    /**
     * @var PDO
     */
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    public static function create($id, $author, $title, $content, $date, $cost)
    {
        $post = new Post;
        
        return $post
            ->setPostId($id)
            ->setAuthor($author)
            ->setTitle($title)
            ->setContent($content)
            ->setDate($date)
            ->setCost($cost);
    }

    public function find($postId)
    {
        $sql  = $this->db->prepare("SELECT * FROM posts WHERE postId = ?");
        $sql->execute(array($postId));
        $row = $sql->fetch();

        if($row === false) {
            return false;
        }

        return $this->makeFromRow($row);
    }

    private function fetchPosts($sql)
    {
        $results = $this->db->query($sql);

        if($results === false) {
            return [];
            throw new \Exception('PDO error in posts paying()');
        }

        $fetch = $results->fetchAll();
        if(count($fetch) == 0) {
            return false;
        }

        return new PostCollection(
            array_map([$this, 'makeFromRow'], $fetch)
        );
    }

    public function all()
    {
        $sql   = "SELECT * FROM posts";

        return $this->fetchPosts($sql);
    }

    public function paying()
    {
        $sql = "SELECT *
                FROM posts, users
                WHERE posts.cost > 0
                AND users.banknumber != ''";

        return $this->fetchPosts($sql);
    }

    public function makeFromRow($row)
    {
        return static::create(
            $row['postId'],
            $row['author'],
            $row['title'],
            $row['content'],
            $row['date'],
            $row['cost']
        );

       //  $this->db = $db;
    }

    public function deleteByPostid($postId)
    {
        $query = $this->db->prepare("DELETE FROM posts WHERE postid=?");
        return $query->execute(array($postId));
        //return $this->db->exec(
        //    sprintf("DELETE FROM posts WHERE postid='%s';", $postId));
    }


    public function save(Post $post)
    {
        $title   = $post->getTitle();
        $author = $post->getAuthor();
        $content = $post->getContent();
        $date    = $post->getDate();
        $cost    = $post->getCost();

        if ($post->getPostId() === null) {
            $query = $this->db->prepare("INSERT INTO posts (title, author, content, date, cost) "
                . "VALUES (?, ?, ?, ?, ?)");
        }

        $query->execute(array($title, $author, $content, $date, $cost));

        return $this->db->lastInsertId();
    }
}

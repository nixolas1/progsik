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

    public function find($postId, $isDoctor, $username)
    {
        if($isDoctor == 1){
            $sql = $this->db->prepare("SELECT * FROM posts WHERE postId= ?");
            $sql->execute(array($postId));
        }else{
            $sql = $this->db->prepare("SELECT * 
                FROM posts, users
                WHERE posts.cost <= 0 AND postId = ?
                OR (users.user == ? AND postId = ?
                    )
                GROUP BY posts.postId");
            $sql->execute(array($postId, $username, $postId));
        }
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

    public function all($user)
    {
        $sql   = "SELECT * 
                FROM posts, users
                WHERE posts.cost <= 0
                OR (users.user == '$user' AND posts.answered == ''
                    )
                GROUP BY posts.postId";

        $q1 = $this->fetchPosts($sql);
        $q2 = $this->payedAndAnswered();

        return array($q1, $q2);
    }

    public function allPosts()
    {
        $sql   = "SELECT * 
                FROM posts";

        return $this->fetchPosts($sql);
    }

    public function paying()
    {
        $sql = "SELECT *
                FROM posts, users
                WHERE posts.cost == 1
                AND users.banknumber != ''
                AND posts.answered == ''
                GROUP BY posts.postId";

        return array($this->fetchPosts($sql), $this->payedAndAnswered());
    }

    public function payedAndAnswered()
    {
        $sql = "SELECT distinct *
                FROM posts, users
                WHERE posts.cost == 1
                AND users.banknumber != ''
                AND posts.answered != ''
                GROUP BY posts.postId";
        return $this->fetchPosts($sql);
    }

    public function update_answered($postId, $doctor)
    {
        $query = $this->db->exec("UPDATE posts
                SET answered='$doctor'
                WHERE posts.postId == '$postId'");

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
            $query = $this->db->prepare("INSERT INTO posts (title, author, content, date, cost, answered) "
                . "VALUES (?, ?, ?, ?, ?, ?)");
        }

        $query->execute(array($title, $author, $content, $date, $cost, ""));

        return $this->db->lastInsertId();
    }
}

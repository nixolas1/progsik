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
    
    public static function create($id, $author, $title, $content, $date, $paydoc, $ansbydoc)
    {
        $post = new Post;
        
        return $post
            ->setPostId($id)
            ->setAuthor($author)
            ->setTitle($title)
            ->setContent($content)
            ->setDate($date)
            ->setPayDoc($paydoc)
            ->setAnsByDoc($ansbydoc);
    }



    // VASKAD
    public function find($postId)
    {
        $query = "SELECT * FROM posts WHERE postId = :postId ";
        $query_params = array( ':postId' => $postId); 

        try { 
            $stmt = $this->db->prepare($query);
            $stmt->execute($query_params); 
            $row = $stmt->fetch();
            if($row === false) {
                return false;
            }
            return $this->makeFromRow($row);
        }
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
    }


    public function all()
    {
        $sql   = "SELECT * FROM posts";
        $results = $this->db->query($sql);

        if($results === false) {
            return [];
            throw new \Exception('PDO error in posts all()');
        }

        $fetch = $results->fetchAll();
        if(count($fetch) == 0) {
            return false;
        }

        return new PostCollection(
            array_map([$this, 'makeFromRow'], $fetch)
        );

    }

    public function makeFromRow($row)
    {
        return static::create(
            $row['postId'],
            $row['author'],
            $row['title'],
            $row['content'],
            $row['date'],
            $row['paydoc'],
            $row['ansbydoc']
        );
    }

    public function deleteByPostid($postId)
    {
        $query = "DELETE FROM posts WHERE postid= :postId ";
        $query_params = array( ':postId' => $postId); 


        try { 
            $stmt = $this->db->prepare($query);
            $stmt->execute($query_params); 
            
            return 1;
        }
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

    }


    public function save(Post $post)
    {
  
        $query =  "INSERT INTO posts (title, author, content, date, paydoc) VALUES (:title, :author, :content, :date, :paydoc)";
        $query_params = array( ':title' => $post->getTitle(), ':author' => $post->getAuthor(), ':content' => $post->getContent(), ':date' => $post->getDate(), ':paydoc' => $post->getPayDoc()); 

        try { 
            $stmt = $this->db->prepare($query);
            $stmt->execute($query_params); 
            
            return $this->db->lastInsertId();
        }
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

    }
}

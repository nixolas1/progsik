<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Comment;
use tdt4237\webapp\repository\UserRepository;

class CommentRepository
{

    /**
     * @var PDO
     */
    private $db;


    /*

    Ubrukt query

    const SELECT_BY_ID = "SELECT * FROM moviereviews WHERE id = %s";

    */

    public function __construct(PDO $db)
    {

        $this->db = $db;
        $this->userRepository = new UserRepository($this->db);
    }

    public function save(Comment $comment)
    {

        $id = $comment->getCommentId();
        $author  = $comment->getAuthor();
        $text    = $comment->getText();
        $date = (string) $comment->getDate();
        $postid = $comment->getPost();
        $ansdoc = $comment->getAnsDoc();
        
        if ($comment->getCommentId() === null) {
            $query =  "INSERT INTO comments (author, text, date, ansdoc, belongs_to_post) VALUES (:author, :text, :date, :ansdoc, :postid)";
            $query_params = array( ':author' => $author, ':text' => $text, ':date' => $date, ':ansdoc' => $ansdoc, ':postid' => $postid ); 
        
            try { 
                $stmt = $this->db->prepare($query);
                $stmt->execute($query_params); 
                
                
            }
            catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        }

        if ($ansdoc == 1) {

            $query1 =  "SELECT ansbydoc FROM posts WHERE postId= :postid";
            $price = 7;
            $query_params1 = array(':postid' => $postid ); 
            try { 
                $stmt = $this->db->prepare($query1);
                $stmt->execute($query_params1);
                $rows = $stmt->fetchAll();
                 //if (isset($rows)) {
                $this->userRepository->updateBalance($author, $price);
                //}
            }
            catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

           

            $query =  "UPDATE posts SET ansbydoc = 1 WHERE postId= :postid";
            $query_params = array(':postid' => $postid ); 
        
            try { 
                $stmt = $this->db->prepare($query);
                $stmt->execute($query_params); 
                return 1;
            } 
            catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        }
        return 1;


        /*
        if ($comment->getCommentId() === null) {
            $query = "INSERT INTO comments (author, text, date, belongs_to_post) "
                . "VALUES ('$author', '$text', '$date', '$postid')";
            return $this->db->exec($query);
        }
        */
    }

    

    public function findByPostId($postId)
    {
        $query   = "SELECT * FROM comments WHERE belongs_to_post = :postid";
        $query_params = array( ':postid' => $postId); 

        try { 
            $stmt = $this->db->prepare($query);
            $stmt->execute($query_params); 
            $rows = $stmt->fetchAll();
            return array_map([$this, 'makeFromRow'], $rows);
            
            
        }
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        /*
        $rows = $this->db->query($query)->fetchAll();

        return array_map([$this, 'makeFromRow'], $rows);
        */
    }

    public function makeFromRow($row)
    {
        $comment = new Comment;
        
        return $comment
            ->setCommentId($row['commentId'])
            ->setAuthor($row['author'])
            ->setText($row['text'])
            ->setDate($row['date'])
            ->setAnsDoc($row['ansdoc'])
            ->setPost($row['belongs_to_post']);
    }
}

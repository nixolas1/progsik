<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Comment;

class CommentRepository
{

    /**
     * @var PDO
     */
    private $db;

    public function __construct(PDO $db)
    {

        $this->db = $db;
    }

    public function save(Comment $comment)
    {
        $id = $comment->getCommentId();
        $author  = $comment->getAuthor();
        $text    = $comment->getText();
        $doctor  = $comment->getDoctor();
        $date = (string) $comment->getDate();
        $postid = $comment->getPost();

        if ($comment->getCommentId() === null) {
            $query = $this->db->prepare("INSERT INTO comments (author, text, date, belongs_to_post, doctor_answer) "
                . "VALUES (?, ?, ?, ?, ?)");

            return $query->execute(array($author, $text, $date, $postid, $doctor));

        }
    }

    public function findByPostId($postId)
    {

        $query   = $this->db->prepare("SELECT * FROM comments WHERE belongs_to_post = ?");
        $query->execute(array($postId));

        $rows = $query->fetchAll();
        
        return array_map([$this, 'makeFromRow'], $rows);
    }

    public function makeFromRow($row)
    {
        $comment = new Comment;
        
        return $comment
            ->setCommentId($row['commentId'])
            ->setAuthor($row['author'])
            ->setText($row['text'])
            ->setDate($row['date'])
            ->setPost($row['belongs_to_post'])
            ->setDoctor($row['doctor_answer']);
    }
}

<?php

namespace tdt4237\webapp;

use tdt4237\webapp\models\User;

class Sql
{
    static $pdo;

    function __construct()
    {
    }

    /**
     * Create tables.
     */
    static function up()
    {
        $q1 = "CREATE TABLE users (
            id INTEGER PRIMARY KEY, 
            user VARCHAR(50) NOT NULL UNIQUE, 
            pass VARCHAR(256) NOT NULL, 
            email varchar(50) default null, 
            fullname varchar(50), 
            address varchar(50), 
            postcode varchar (4), 
            age varchar(50), 
            bio varchar(50),
            isadmin INTEGER,
            isdoctor INTEGER default 0,
            banknumber varchar(15)
            );";
        $q6 = "CREATE TABLE posts (
            postId INTEGER PRIMARY KEY AUTOINCREMENT, 
            author TEXT, title TEXT NOT NULL, 
            content TEXT NOT NULL, 
            date TEXT NOT NULL,
            cost varchar(10),
            answered VARCHAR(50) NULL,
            FOREIGN KEY(author) REFERENCES users(user));";
        $q7 = "CREATE TABLE comments(
            commentId INTEGER PRIMARY KEY AUTOINCREMENT, 
            date TEXT NOT NULL, 
            author TEXT NOT NULL, 
            text INTEGER NOT NULL, 
            belongs_to_post INTEGER NOT NULL, 
            FOREIGN KEY(belongs_to_post) REFERENCES posts(postId));";

        self::$pdo->exec($q1);
        self::$pdo->exec($q6);
        self::$pdo->exec($q7);

        print "[tdt4237] Done creating all SQL tables.".PHP_EOL;

        self::insertDummyUsers();
        self::insertPosts();
        self::insertComments();
    }

    static function insertDummyUsers()
    {
        # generate random pass for admin user, from 12-19 chars long so bruteforcing will be discuraged
        $admin_pass = base64_encode(openssl_random_pseudo_bytes(rand(12, 19)));
        $hash1 = Hash::make($admin_pass);
        $hash2 = Hash::make("Testuser123");
        $q1 = "INSERT INTO users(user, pass, isadmin, fullname, address, postcode) VALUES ('admin', '$hash1', 1, 'admin', 'homebase', '9090')";
        $qTest = "INSERT INTO users(user, pass, isadmin, fullname, address, postcode) VALUES ('testuser', '$hash2', 1, 'admin', 'homebase', '9090')";
        
        self::$pdo->exec($q1);
        self::$pdo->exec($qTest);


        print "[tdt4237] Auto-generated admin user pass is: ".$admin_pass.PHP_EOL;
        print "[tdt4237] Done inserting dummy users.".PHP_EOL;
    }

    static function insertPosts() {
        $q4 = "INSERT INTO posts(author, date, title, content, cost, answered) VALUES ('admin', '26082015', 'I have a problem', 'I have a generic problem I think its embarrasing to talk about. Someone help?', '1', NULL)";
        $q5 = "INSERT INTO posts(author, date, title, content, cost, answered) VALUES ('admin', '26082015', 'I also have a problem', 'I generally fear very much for my health', '0', NULL)";

        self::$pdo->exec($q4);
        self::$pdo->exec($q5);
        print "[tdt4237] Done inserting posts.".PHP_EOL;

    }

    static function insertComments() {
        $q1 = "INSERT INTO comments(author, date, text, belongs_to_post) VALUES ('admin', '26082015', 'Don''t be shy! No reason to be afraid here',1)";
        $q2 = "INSERT INTO comments(author, date, text, belongs_to_post) VALUES ('admin', '26082015', 'I wouldn''t worry too much, really. Just relax!',2)";
        self::$pdo->exec($q1);
        self::$pdo->exec($q2);
        print "[tdt4237] Done inserting comments.".PHP_EOL;

    }

    static function down()
    {
        $q1 = "DROP TABLE users";
        $q4 = "DROP TABLE posts";
        $q5 = "DROP TABLE comments";



        self::$pdo->exec($q1);
        self::$pdo->exec($q4);
        self::$pdo->exec($q5);

        print "[tdt4237] Done deleting all SQL tables.".PHP_EOL;
    }
}
try {
    // Create (connect to) SQLite database in file
    Sql::$pdo = new \PDO('sqlite:app.db');
    // Set errormode to exceptions
    Sql::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo $e->getMessage();
    exit();
}

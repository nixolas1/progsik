<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\Post;

class PostValidation {

    private $validationErrors = [];

    public function __construct($author, $title, $content) {
        return $this->validate($author, $title, $content);
    }

    public function isGoodToGo()
    {
        return \count($this->validationErrors) ===0;
    }

    public function getValidationErrors()
    {
    return $this->validationErrors;
    }

    public function validate($author, $title, $content)
    {
        if ($author == null) {
            $this->validationErrors[] = "Author needed";
        }

        if ($title == null) {
            $this->validationErrors[] = "Title needed";
        }
        elseif(strlen($title) > 250){
            $this->validationErrors[] = "The title cannot be longer than 250 characters";
        }


        if (empty($content)) {
            $this->validationErrors[] = "Text needed";
        }
        elseif(strlen($content) > 20000){
            $this->validationErrors[] = "Content cannot be more than 20 000 characters.";
        }

        return $this->validationErrors;
    }


}

<?php

namespace tdt4237\webapp\models;

use ArrayObject;

class MovieCollection extends ArrayObject
{

    public function sortByTitle()
    {
        $this->uasort(function (Movie $a, Movie $b) {
            return strcmp($a->getName(), $b->getName());
        });
    }
}

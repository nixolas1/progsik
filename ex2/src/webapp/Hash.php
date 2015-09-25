<?php

namespace tdt4237\webapp;

class Hash
{
    public function __construct()
    {
    }

    public function make($plaintext)
    {
        return hash('sha512', $plaintext);
    }

    public function check($plaintext, $hash)
    {
        return $this->make($plaintext) === $hash;
    }
}

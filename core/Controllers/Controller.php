<?php

namespace App\Controllers;

class Controller 
{
    protected $c;
    
    public function __construct($c)
    {
        $this->c = $c;
    }
    
    public function __get($prop)
    {
        if ($this->c->{$prop}) {
            return $this->c->{$prop};
        }
    }
    
}
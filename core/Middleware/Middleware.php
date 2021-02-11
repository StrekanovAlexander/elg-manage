<?php

namespace App\Middleware;

class Middleware
{
    protected $c;
    
    public function __construct($c)
    {
        $this->c = $c;
    }
}
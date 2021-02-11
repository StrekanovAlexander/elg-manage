<?php

namespace App\Middleware;

class CsrfMiddleware extends Middleware
{
    public function __invoke($req, $res, $next)
    {
        $this->c->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . 
                    $this->c->csrf->getTokenNameKey() . '" value="' . 
                    $this->c->csrf->getTokenName() . '">
                <input type="hidden" name="' . 
                    $this->c->csrf->getTokenValueKey() . '" value="' . 
                    $this->c->csrf->getTokenValue() . '">',
        ]);
        $res = $next($req, $res);
        return $res;
    }
}
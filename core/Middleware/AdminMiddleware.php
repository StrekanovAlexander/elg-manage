<?php

namespace App\Middleware;

class AdminMiddleware extends Middleware {

    public function __invoke($req, $res, $next)
    {
      if ($this->c->auth->check()) {
          return $res->withRedirect($this->c->router->pathFor('home.index'));
      }
      $res = $next($req, $res);
      return $res;
    }
}
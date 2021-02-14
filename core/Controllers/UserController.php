<?php

namespace App\Controllers;

// use App\Models\BaseRate;

class UserController extends Controller
{
    public function login($req, $res)
    {
        return $this->view->render($res, 'user/login.twig');
    }

    public function postLogin($req, $res)
    {
        $this->auth->attempt(
            $req->getParam('username'), 
            $req->getParam('password')
        );
        return $res->withRedirect($this->router->pathFor('home.index'));
    }

    public function logout($req, $res) {
        $this->auth->logout();
        return $res->withRedirect($this->router->pathFor('user.login'));
    }
     
}   
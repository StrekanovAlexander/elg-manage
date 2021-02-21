<?php

namespace App\Controllers;

use App\Models\User;

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

    public function logout($req, $res)
    {
        $this->auth->logout();
        return $res->withRedirect($this->router->pathFor('user.login'));
    }

    public function edit($req, $res)
    {
        return $this->view->render($res, 'user/edit.twig');
    }

    public function update($req, $res)
    {
        $user = User::first();
        // $2y$10$w6vWc.3GFM55dGH9CQnDgurhmuEreDaGosAml2q/ISqfJec/kCz7e
        $passwordCurrent = $req->getParam('password-current');
        
        if (!password_verify($passwordCurrent, $user->password)) {
            $this->flash->addMessage('error', 'Текущий пароль указан неверно!');
            return $res->withRedirect($this->router->pathFor('user.edit'));
        }

        $user->update([
            'password' => password_hash($req->getParam('password'), PASSWORD_DEFAULT),
        ]);

        $this->flash->addMessage('message', 'Данные доступа были изменены.');
        return $res->withRedirect($this->router->pathFor('user.index'));
    }

    public function index($req, $res)
    {
        return $this->view->render($res, 'user/index.twig');
    }
     
}   
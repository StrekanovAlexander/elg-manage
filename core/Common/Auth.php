<?php

namespace App\Common;

use App\Models\User;

class Auth {
    
    public function attempt($username, $password)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->id;
            return true;
        }

        return false;
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

}
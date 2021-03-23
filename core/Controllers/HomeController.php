<?php

namespace App\Controllers;

use App\Models\BaseRate;

class HomeController extends Controller
{
    public function index($req, $res)
    {
        $base_rates = BaseRate::actual();
        return $this->view->render($res, 'base/index.twig', [
            'base_rates' => $base_rates,
        ]);
    }
}    
<?php

namespace App\Controllers;

use App\Models\BaseRate;

class HomeController extends Controller
{
    public function index($req, $res)
    {
        $base_rates = BaseRate::orderBy('curr_id', 'ASC')->get();
        return $this->view->render($res, 'base-rate/index.twig', [
            'base_rates' => $base_rates,
        ]);
    }
}    
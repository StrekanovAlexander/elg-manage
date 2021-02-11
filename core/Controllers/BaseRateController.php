<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;

class BaseRateController extends Controller
{
    public function index($req, $res)
    {
        $base_rates = BaseRate::orderBy('curr_id', 'ASC')->get();
        return $this->view->render($res, 'base-rate/index.twig', [
            'base_rates' => $base_rates,
        ]);
    }

    public function create($req, $res)
    {
        return $this->view->render($res, 'base-rate/create.twig', [
            'currs' => Curr::orderBy('id', 'ASC')
                ->where('is_main', false)
                ->where('is_actual', true)->get(),
        ]);
    }
} 
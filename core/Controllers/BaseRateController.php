<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;

class BaseRateController extends Controller
{
    public function index($req, $res)
    {
        return $this->view->render($res, 'base/index.twig', [
            'base' => BaseRate::actual(),
        ]);
    }

    public function create($req, $res)
    {
        // $currs = BaseRate::existsActual() ? BaseRate::actual() : Curr::actual();
        $currs = BaseRate::actualBase();
        return $this->view->render($res, 'base/create.twig', [
           'currs' => $currs,
        ]);
    }

    public function store($req, $res)
    {
        $timestamp = date('Y-m-d H:i:s');
        $arr = \App\Common\StringUtil::cleanParams($req->getParams(), [
            'csrf_name', 
            'csrf_value',
        ], 3);

        foreach($arr as $value) {
            BaseRate::create([
                'curr_id' => $value[0],
                'rate_buy' => $value[1],
                'steps' => $value[2],
                'created_at' => $timestamp,
            ]);
        }

        $this->flash->addMessage('message', 'Базовые курсы успешно установлены');
        return $this->response->withRedirect($this->router->pathFor('rules.set'));
    }

    public function createCross($req, $res)
    {
        $base = BaseRate::actualBase();
        $currs = Curr::orderBy('id')
            ->where('is_actual', true)
            ->where('is_cross', true)
            ->get();
        $eq_curr = BaseRate::actualById(2); // USD ID    
        return $this->view->render($res, 'base/create-cross.twig', [
           'base' => $base,
           'currs' => $currs,
           'eq_curr' => $eq_curr,
        ]);
    }

    public function storeCross($req, $res)
    {
        $timestamp = BaseRate::max('created_at');
        $arr = \App\Common\StringUtil::cleanParams($req->getParams(), [
            'csrf_name', 
            'csrf_value',
        ], 6);

        foreach($arr as $value) {
            BaseRate::create([
                'curr_id' => $value[0],
                'rate_cross' => $value[1],
                'steps_cross_buy' => $value[2],
                'steps_cross_sale' => $value[3],
                'rate_buy' => $value[1] * $value[4],
                'rate_sale' => $value[1] * $value[5],
                'created_at' => $timestamp,
            ]);
        }

        $this->flash->addMessage('message', 'Базовые курсы - кросс успешно установлены');
        return $this->response->withRedirect($this->router->pathFor('rules.set'));

    }

} 
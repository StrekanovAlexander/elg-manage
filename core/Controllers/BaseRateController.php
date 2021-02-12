<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;

class BaseRateController extends Controller
{
    public function index($req, $res)
    {
        return $this->view->render($res, 'base-rate/index.twig', [
            'base_rates' => BaseRate::actual(),
        ]);
    }

    public function create($req, $res)
    {
        $currs = BaseRate::existsActual() ? BaseRate::actual() : Curr::actual();
        return $this->view->render($res, 'base-rate/create.twig', [
           'currs' => $currs,
        ]);
    }

    public function store($req, $res)
    {
        $timestamp = date('Y-m-d H:i:s');
        $str = '';

        foreach($req->getParams() as $key => $value) {
            if (($key == 'csrf_name') || ($key == 'csrf_value')) continue; 
            $str .= $value . '|';
        }

        $arr= explode('|', mb_substr($str, 0, -1));
        $arr = array_chunk($arr, 3);

        foreach($arr as $value) {
            BaseRate::create([
                'curr_id' => $value[0],
                'rate_buy' => $value[1],
                'steps' => $value[2],
                'created_at' => $timestamp,
            ]);
        }

        $this->flash->addMessage('message', 'Базовые курсы валют успешно установлены');
        return $this->response->withRedirect($this->router->pathFor('base-rate.index'));
    }
} 
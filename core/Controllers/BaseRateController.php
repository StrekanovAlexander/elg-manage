<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;
use App\Models\Place;
use App\Models\Rule;

class BaseRateController extends Controller
{
    public function index($req, $res)
    {
        return $this->view->render($res, 'base/index.twig', [
            'base' => BaseRate::actual(),
        ]);
    }

    public function index2($req, $res)
    {
        $bases = BaseRate::bases();
        $base_rates = BaseRate::actual();
        $currs = Curr::orderBy('id')
            ->where('is_actual', true)
            ->where('is_main', false)
            ->get();

        $places = Place::orderBy('is_base', 'DESC')
            ->orderBy('full_name', 'ASC')
            ->where('is_actual', true)
            ->get();

        $rules = Rule::where('is_actual', true)->get();

        // Crosses
        // $base = BaseRate::actualBase();
        $cross_eqv = BaseRate::actualById(2); //  Curr::currCrossEq();
        $bases_cross = BaseRate::actualBaseCross();
        // End crosses

        return $this->view->render($res, 'base/index2.twig', [
            'bases' => $bases,
            'base_rates' => $base_rates,
            'currs' => $currs,
            'places' => $places,
            'rules' => $rules,
            'cross_eqv' => $cross_eqv,
            'bases_cross' => $bases_cross,
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
            $curr = Curr::find($value[0]); 
            $diff = $value[2] * $curr->step_size;
            BaseRate::create([
                'curr_id' => $value[0],
                'rate_buy' => $value[1],
                'rate_sale' => $value[1] + $diff,
                'steps' => $value[2],
                'is_cross' => false,
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
            BaseRate::drop($value[0], $timestamp);
            $curr = Curr::find($value[0]); 
            $oper_cross = $curr->oper_cross;
            
            $rate_cross = $value[1];
            $steps_cross_buy = $value[2];
            $steps_cross_sale = $value[3];
            $rate_base_buy = $value[4];
            $rate_base_sale = $value[5];
            $rate_cross_buy = $rate_cross - ($steps_cross_buy * $curr->step_size);
            $rate_cross_sale = $rate_cross - ($steps_cross_sale * $curr->step_size);
            if ($curr->oper_cross == '*') {
                $rate_buy = $rate_base_buy * $rate_cross_buy;
                $rate_sale = $rate_base_buy * $rate_cross_sale;
            } else {
                $rate_buy = $rate_base_buy / $rate_cross_buy;
                $rate_sale = $rate_base_sale / $rate_cross_sale;
            }
        
            BaseRate::create([
                'curr_id' => $value[0],
                'rate_buy' => $rate_buy,
                'rate_sale' => $rate_sale,
                'rate_cross' => $rate_cross,
                'rate_cross_buy' => $rate_cross_buy,
                'rate_cross_sale' => $rate_cross_sale,
                'steps_cross_buy' => $steps_cross_buy,
                'steps_cross_sale' => $steps_cross_sale,
                'rate_base_buy' => $rate_base_buy,
                'rate_base_sale' => $rate_base_sale,
                'is_cross' => 1,
                'created_at' => $timestamp,
            ]);
        }

        $this->flash->addMessage('message', 'Базовые курсы - кросс успешно установлены');
        return $this->response->withRedirect($this->router->pathFor('rules.set'));

    }

} 
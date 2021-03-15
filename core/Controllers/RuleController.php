<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;
use App\Models\Place;
use App\Models\Rule;

class RuleController extends Controller
{
    public function details($req, $res, $args)
    {
        return $this->view->render($res, 'rule/details.twig', [
            'place' => Place::byId($args['id']),
            'rules' => Rule::byPlace($args['id']),
        ]);
    }

    public function edit($req, $res, $args)
    {
        return $this->view->render($res, 'rule/edit.twig', [
            'place' => Place::byId($args['id']),
            'rules' => Rule::byPlace($args['id']),
        ]);
    }

    public function update($req, $res)
    {

        $arr = \App\Common\StringUtil::cleanParams($req->getParams(), [
            'id',
            'csrf_name', 
            'csrf_value',
        ], 3);

        foreach($arr as $value) {
            $rule = Rule::find($value[0]);
            $rule->update([
                'diff_buy' => $value[1],
                'diff_sale' => $value[2],
            ]);
        }

        $this->flash->addMessage('message', 'Настройки курсов валют успешно записаны');
        return $this->response->withRedirect($this->router->pathFor('rule.details', [
            'id' => $req->getParam('id'), 
        ]));
    }

    public function getRules($req, $res) 
    {
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

        return $this->view->render($res, 'rule/rules.twig', [
            'base_rates' => $base_rates,
            'currs' => $currs,
            'places' => $places,
            'rules' => $rules,
        ]);
    }

    public function storeRules($req, $res) 
    {
        $currs = Curr::orderBy('id')
            ->where('is_actual', true)
            ->where('is_main', false)
            ->get();

        $places = Place::orderBy('full_name')
            ->where('is_actual', true)
            ->where('is_base', false)
            ->get();    

        foreach($currs as $curr) {
            foreach($places as $place) {
                $rule = Rule::where('place_id', $place->id)
                    ->where('curr_id', $curr->id)
                    ->first();
                $rule->update([
                    'diff_buy' => $req->getParam('diff_buy_' . $curr->id . '_' . $place->id),
                    'diff_sale' => $req->getParam('diff_sale_' . $curr->id . '_' . $place->id),
                ]);
            }    
        }    
    
        $this->flash->addMessage('message', 'Настройки курсов валют успешно записаны');
        return $this->response->withRedirect($this->router->pathFor('rules.set'));

    }
}    
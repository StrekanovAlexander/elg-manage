<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;
use App\Models\Message;
use App\Models\Place;
use App\Models\Rule;
use App\Models\Rate;
use App\Common\Emoji;
use App\Common\Settings;
use App\Common\StringUtil;

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

    public function storeBaseAndRules($req, $res) 
    {
        $currs = Curr::orderBy('id')->where('is_actual', true)
            ->where('is_main', false)->get();

        $places = Place::orderBy('full_name')->where('is_actual', true)
            ->where('is_base', false)->get();    

        foreach($currs as $curr) {
            foreach($places as $place) {
                $rule = Rule::where('place_id', $place->id)
                    ->where('curr_id', $curr->id)->first();
                $rule->update([
                    'diff_buy' => $req->getParam('diff_buy_' . $curr->id . '_' . $place->id),
                    'diff_sale' => $req->getParam('diff_sale_' . $curr->id . '_' . $place->id),
                ]);
            }    
        }    
    
        $timestamp = date('Y-m-d H:i:s');

        $currs = Curr::orderBy('id')
            ->where('is_actual', true)
            ->where('is_main', false)
            ->get();

        foreach($currs as $curr) {
            $rate_buy = $curr->is_cross ? 
                $req->getParam('base_rate_cross_buy_id_' . $curr->id) :
                $req->getParam('base_rate_buy_id_' . $curr->id); 
            $rate_sale = $curr->is_cross ? 
                $req->getParam('base_rate_cross_sale_id_' . $curr->id) :
                $req->getParam('base_rate_sale_id_' . $curr->id); 
             
            BaseRate::create([
                'curr_id' => $curr->id,
                'rate_buy' => $rate_buy,
                'rate_sale' => $rate_sale,
                'rate_cross' => $curr->is_cross ? $req->getParam('rate_cross_id_' . $curr->id) : null,
                'steps_cross_buy' => $curr->is_cross ? $req->getParam('steps_cross_buy_id_' . $curr->id) : null,
                'steps_cross_sale' => $curr->is_cross ? $req->getParam('steps_cross_sale_id_' . $curr->id) : null,
                'is_cross' => $curr->is_cross ? true : false,
                'created_at' => $timestamp,
            ]);

        }    
    
        $this->flash->addMessage('message', 'Курсы были записаны в базу.');
        return $this->response->withRedirect($this->router->pathFor('base.index2'));

    }

    public function sendToChannel($req, $res)
    {
        $messages = Message::orderBy('position')
            ->where('prefix', 'rates_header')
            ->where('is_actual', true)
            ->first();
        $s = Emoji::decode($messages->content);    

        $base_place = Place::where('is_actual', true)->where('is_base', true)->first();
        $rates = Rate::actualByPlace($base_place->id);
        
        $s .= Rate::ratesToStr($rates) . "\n\n";
        
        $messages = Message::orderBy('position')
            ->where('prefix', 'channel')
            ->where('is_actual', true)
            ->get();

        foreach ($messages as $message) {
            $s .= Emoji::decode($message->content) . "\n\n";
        }

        // Test settings
        //   $token = '1689953716:AAHQDVsVUYOJBhVdgTLyS8ojFKdm_jLHgCA'; 
        //   $chat_id = '-1001242571685';
        
        // Works settings
        // $token = '1647327669:AAHS4UGQmxhsVNZmkZH_ecsTwrX7gWwTsWE'; 
        // $chat_id = '-1001378695678';

        $token = Settings::$global['main_bot_token']; 
        $chat_id = Settings::$global['main_chat_id'];
        
        $message = $req->getParam('message') ? $req->getParam('message') : '';
        $message = $s . $message;

        \App\Common\Bot::sendToChat($token, $chat_id, $message);

        $this->flash->addMessage('message', 'Сообщение в Telegram было отправлено.');
        return $this->response->withRedirect($this->router->pathFor('base.index2'));

    }
  
} 
<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;
use App\Models\Place;
use App\Models\Rule;
use App\Models\Rate;
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
        // return $this->response->withRedirect($this->router->pathFor('home.index'));

    }

    public function sendToChannel($req, $res)
    {
        $base_place = Place::where('is_actual', true)->where('is_base', true)->first();
        $rates = Rate::actualByPlace($base_place->id);
        $s = 'ОПТОВЫЙ КУРС ВАЛЮТ ' . hex2bin('F09F92B5') . ' от 300 $, €';

        $s .= sprintf("\n%-10s  %' 10s  %' 10s","Валюта","Покупка","Продажа");
        foreach ($rates as $rate) {
            $s .= sprintf(
            "\n%-10s %' 9s  %' 9s", 
            Curr::iconed($rate->curr),
            number_format($rate['rate_buy'], $rate->curr->precision_size), 
            number_format($rate['rate_sale'], $rate->curr->precision_size)
            );
        }

        $s .= "\n\n";
        $s .= hex2bin('E280BCEFB88F') . " Курс в течении дня  может меняться в зависимости от ситуации на валютном рынке как в " . hex2bin('F09F9388') . ", так и в " . hex2bin('F09F9389') . ".";

        $s .= "\n\n" . hex2bin('F09F93B2') . "0961010000 Владислав (Telegram, Viber, WhatsApp)\nНаписать менеджеру - @ELG_obmen";

        $s .= "\n\nДенежные переводы по Украине и за рубежом:\n";
        $s .= hex2bin('F09F93B2') . " 0981010000 Татьяна  (Telegram)\nНаписать менеджеру @perevody_ELG";

        $s .= "\n\nПроверяйте актуальный курс в Вашем городе на сайте: https://elg.co.ua\nили у нашего бота @elgzp_bot";

        $s .= "\n\n";

        // var_dump($s);
        // die();
        
        // Test settings
        $token = '1689953716:AAHQDVsVUYOJBhVdgTLyS8ojFKdm_jLHgCA'; 
        $chat_id = '-1001242571685';
        
        // Works settings
        // $token = '1647327669:AAHS4UGQmxhsVNZmkZH_ecsTwrX7gWwTsWE'; 
        // $chat_id = '-1001378695678';
        $message = $req->getParam('message') ? $req->getParam('message') : '';
        $message = $s . $message;

        \App\Common\Bot::sendToChat($token, $chat_id, $message);

        $this->flash->addMessage('message', 'Сообщение в Telegram было отправлено.');
        return $this->response->withRedirect($this->router->pathFor('base.index2'));
    }

    // public function sendInfoToChannel($req, $res)
    // {
        //  Test settings
        //  $token = '1689953716:AAHQDVsVUYOJBhVdgTLyS8ojFKdm_jLHgCA'; 
        //  $chat_id = '-1001242571685';

        // Work settings
        // $token = '1777201537:AAFqqrlkkuLjtCstVKfbAfoxI8YeDM136xU'; 
        // $chat_id = '-1001268528953';

        // $message = 'курс';
        // \App\Common\Bot::sendToChat($token, $chat_id, $message);
        // $this->flash->addMessage('message', 'Оповещение об изменении курсов валют было отправлено.');
        // return $this->response->withRedirect($this->router->pathFor('base.index2'));
    // }
   
} 
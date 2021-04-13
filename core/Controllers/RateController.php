<?php

namespace App\Controllers;

use App\Models\Curr;
use App\Models\Dep;
use App\Models\Place;
use App\Models\Rate;
use App\Common\StringUtil;

class RateController extends Controller
{
    public function details($req, $res, $args)
    {
        return $this->view->render($res, 'rate/details.twig', [
            'place' => Place::byId($args['id']),
            'rates' => Rate::actualByPlace($args['id']),
        ]);
    }

    public function json($req, $res, $args)
    {
        $json = $this->ratesByPlace($args['id']);  
        $res->getBody()->write(json_encode($json));
        return  $res->withHeader('Content-type', 'application/json; charset=utf-8');

    }

    public function json2($req, $res)
    {
        $json = [];
        $json['timestamp'] = date('Y-m-d H:i:s');
        $places = Place::actual();
        foreach($places as $place) {
            $json[] = [
                'id' => $place->id,
                'name' => $place->full_name,
                'rates' => $this->ratesByPlace($place->id),    
            ];         
        } 

        $res->getBody()->write(json_encode($json, JSON_UNESCAPED_UNICODE));
        // return  $res->withHeader('Content-type', 'application/json; charset=utf-8');
        return  $res->withHeader('Content-type', 'application/json; charset=utf-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

    }

    private function ratesTable()
    {
        $currs = Curr::orderBy('id')->where('is_actual', true)->where('is_main', false)->get();
        // $places = Place::orderBy('id')->where('is_actual', true)->get();
        $places = Place::actual();
        $tds = '';

        foreach($places as $place) {
            $tds .= StringUtil::setTag('th', $place->full_name, 'border: 1px solid gray'); 
        }
       
        $theader = StringUtil::setTag('tr', StringUtil::setTag('td', '') . $tds);
        
        $trs_buy = '';
        $trs_sale = '';

        foreach($currs as $curr) {
            $tds_buy = StringUtil::setTag('td', $curr->short_name . '_пок',  'border: 1px solid gray');
            $tds_sale = StringUtil::setTag('td', $curr->short_name . '_прод', 'border: 1px solid gray');
            $rates = Rate::actualByCurr($curr->id);
            foreach($rates as $rate) {
                $tds_buy .= StringUtil::setTag(
                    'td', 
                    number_format($rate->rate_buy, $curr->precision_size), 
                    'text-align:right;border: 1px solid gray'
                );
                $tds_sale .= StringUtil::setTag(
                    'td', 
                    number_format($rate->rate_sale, $curr->precision_size), 
                    'text-align:right;border: 1px solid gray'
                );
            }
            $trs_buy .= StringUtil::setTag('tr', $tds_buy, 'background-color: ' . $curr->color->full_name);
            $trs_sale .= StringUtil::setTag('tr', $tds_sale, 'background-color: ' . $curr->color->full_name);
        }
        $trs_empty = StringUtil::setTag('tr', StringUtil::setTag('td', '', 'height: 1em'));
        return StringUtil::setTag('table', $theader . $trs_buy . $trs_empty . $trs_sale, 'border-collapse: collapse');
        
    }

    public function send($req, $res)
    {
        $emails = $this->emailList();
        // $emails = [];
        array_push($emails, '8899897@gmail.com');
        
        $title = 'Курсы валют ' . date('H:i:s d.m.Y');
        $body = StringUtil::setTag('h4', $title, 'font-weight: normal');
        $body .= $this->ratesTable();

        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername('fin.lombard@gmail.com')
            ->setPassword('Aksafefy');

        // $transport = (new \Swift_SmtpTransport('mail.elg.co.ua', 587, 'tls'))
        //     ->setUsername('manager@elg.co.ua')
        //     ->setPassword('4Rs68BUf7u');
    
        $mailer = new \Swift_Mailer($transport);
        
        $message = (new \Swift_Message($title))
            ->setFrom(['fin.lombard@gmail.com' => 'Elg Manager'])
            ->setTo($emails)
            ->setBody($body, 'text/html');

        $result = $mailer->send($message);
        if ($result) {
            $token = '1777201537:AAFqqrlkkuLjtCstVKfbAfoxI8YeDM136xU'; 
            $chat_id = '-1001268528953';
            \App\Common\Bot::sendToChat($token, $chat_id, 'курс');
            $this->flash->addMessage('message', 'Курсы валют отправлены на отделения.');
        }

        // return $this->response->withRedirect($this->router->pathFor('home.index'));
        return $this->response->withRedirect($this->router->pathFor('base.index2'));
    }

    private function emailList()
    {
        $emails = [];
        $deps = Dep::where('is_actual', true)
            ->where('is_mail', true)
            ->get();
        foreach($deps as $dep) {
            $emails[] = $dep->email;
        }
        return $emails;
    }

    private function ratesByPlace($id)
    {
        $rates = [];
        $actualRates = Rate::actualByPlace($id);
        
        foreach($actualRates as $rate) {
            $rates[] = [
                'id' => $rate->curr->id,
                'currency' => $rate->curr->short_name,
                'buy' => number_format($rate->rate_buy, $rate->curr->precision_size),
                'sale' => number_format($rate->rate_sale, $rate->curr->precision_size),
            ];         
        }
        
        return $rates;

    }
}    
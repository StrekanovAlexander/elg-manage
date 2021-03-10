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
        $rates = Rate::actualByPlace($args['id']);
        $arr = [];
        foreach($rates as $value) {
            $arr[] = [
                'id' => $value->curr->id,
                'currency' => $value->curr->short_name,
                'buy' => $value->rate_buy,
                'sale' => $value->rate_sale,
            ];         
        } 

        $res->getBody()->write(json_encode($arr));
        return  $res->withHeader('Content-type', 'application/json; charset=utf-8');

    }

    private function ratesTable()
    {
        $currs = Curr::orderBy('id')->where('is_actual', true)->where('is_main', false)->get();
        $places = Place::orderBy('id')->where('is_actual', true)->get();
        $tds = '';

        foreach($places as $place) {
            $tds .= StringUtil::setTag('th', $place->full_name, 'border: 1px solid gray'); 
        }
       
        $theader = StringUtil::setTag('tr', StringUtil::setTag('td', '') . $tds);
        
        $trs_buy = '';
        $trs_sale = '';
        // $tds_buy = '';
        // $tds_sale = '';
        foreach($currs as $curr) {
            $tds_buy = StringUtil::setTag('td', $curr->short_name . '_пок',  'border: 1px solid gray');
            $tds_sale = StringUtil::setTag('td', $curr->short_name . '_прод', 'border: 1px solid gray');
            $rates = Rate::actualByCurr($curr->id);
            foreach($rates as $rate) {
                $tds_buy .= StringUtil::setTag('td', $rate->rate_buy, 'text-align:right;border: 1px solid gray');
                $tds_sale .= StringUtil::setTag('td', $rate->rate_sale, 'text-align:right;border: 1px solid gray');
            }
            $trs_buy .= StringUtil::setTag('tr', $tds_buy);
            $trs_sale .= StringUtil::setTag('tr', $tds_sale);
        }
        $trs_empty = StringUtil::setTag('tr', StringUtil::setTag('td', '', 'height: 1em'));
        return StringUtil::setTag('table', $theader . $trs_buy . $trs_empty . $trs_sale, 'border-collapse: collapse');
        
    }

    public function send($req, $res)
    {

        $emails = $this->emailList();

        $title = 'Курсы валют ' . date('H:i:s d.m.Y');
        $body = StringUtil::setTag('h4', $title, 'font-weight: normal');
        $body .= $this->ratesTable();

        $transport = (new \Swift_SmtpTransport('mail.elg.co.ua', 587, 'tls'))
            ->setUsername('manager@elg.co.ua')
            ->setPassword('4Rs68BUf7u');
    
        $mailer = new \Swift_Mailer($transport);
        
        $message = (new \Swift_Message($title))
            ->setFrom(['manager@elg.co.ua' => 'Elg Manager'])
            // ->setTo(['alexis.s@i.ua', '8899897@gmail.com'])
            ->setTo($emails)
            ->setBody($body, 'text/html');

        $result = $mailer->send($message);
        $this->flash->addMessage('message', 'Актуальные курсы валют отправлены на отделения.');
        return $this->response->withRedirect($this->router->pathFor('base-rate.index'));
    }

    private function emailList()
    {
        $emails = [];
        $deps = Dep::where('is_actual', true)->get();
        foreach($deps as $dep) {
            $emails[] = $dep->email;
        }
        return $emails;
    }
}    
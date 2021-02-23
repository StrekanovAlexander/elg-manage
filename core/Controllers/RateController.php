<?php

namespace App\Controllers;

use App\Models\Curr;
use App\Models\Place;
use App\Models\Rate;
use App\Common\String;

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
        $header = '';
        foreach($places as $place) {
            $header .= '<td>' . $place->full_name . '</td>'; 
        }
        $header = '<tr><td>Валюта</td>' . $header . '</tr>';
        $rate_buy = '';
        foreach($currs as $curr) {
            $rate_buy .= '<td>' . $curr->short_name . '&nbsp;пок</td>';
            $rate_sale .= '<td>' . $curr->short_name . '&nbsp;прод</td>';
            $data = Rate::actualByCurr($curr->id);
            foreach($data as $el) {
                $rate_buy .= '<td align="right">' . $el->rate_buy . '</td>';
                $rate_sale .= '<td align="right">' . $el->rate_sale . '</td>';
            }
            $rate_buy = '<tr>' . $rate_buy . '</tr>';
            $rate_sale = '<tr>' . $rate_sale . '</tr>';
        }

        return '<table border="1">' . $header . $rate_buy . $rate_sale . '</table>';
    }

    private function ratesTable2()
    {
        $currs = Curr::orderBy('id')->where('is_actual', true)->where('is_main', false)->get();
        $places = Place::orderBy('id')->where('is_actual', true)->get();
        $tds = '';

        foreach($places as $place) {
            $tds .= String::setTD($place->full_name); 
        }
        
        $theader = String::setTR(String::setTD('') . $tds);
        
        $trs_buy = '';
        $trs_sale = '';
        foreach($currs as $curr) {
            $trs_buy .= String::setTD($curr->short_name . '_пок');
            $trs_sale .= String::setTD($curr->short_name . '_прод');
            $rates = Rate::actualByCurr($curr->id);
            foreach($rates as $rate) {
                $trs_buy .= String::setTD($rate->rate_buy, 'right');
                $trs_sale .= String::setTD($rate->rate_sale, 'right');;
            }
            $trs_buy = String::setTR($trs_buy);
            $trs_sale = String::setTR($trs_sale);
        }

        return String::setTB($theader . $trs_buy . $trs_sale);
        
    }

    public function send($req, $res)
    {
        $title = 'Курсы валют ' . date('H:i:s d.m.Y');
        $body = '<h3>' . $title . '</h3>';
        $body .= $this->ratesTable2();
        
        echo $body;
        die();

        // var_dump($title);
        // die();

        // $transport = (new \Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
        //     ->setUsername('strekanov.alexander@gmail.com')
        //     ->setPassword('gooberbotsman1967');
    
        // $mailer = new \Swift_Mailer($transport);
        
        // $message = (new \Swift_Message($title))
        //     ->setFrom(['strekanov.alexander@gmail.com' => 'Elg Manager'])
        //     ->setTo(['alexis.s@i.ua'])
        //     ->setBody($body, 'text/html');

        // $result = $mailer->send($message);
        // $this->flash->addMessage('message', 'Актуальные курсы валют отправлены на отделения.');
        // return $this->response->withRedirect($this->router->pathFor('base-rate.index'));
    }
}    
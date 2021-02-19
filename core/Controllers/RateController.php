<?php

namespace App\Controllers;

use App\Models\Rate;
use App\Models\Place;

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
}    
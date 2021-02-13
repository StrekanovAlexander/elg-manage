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
}    
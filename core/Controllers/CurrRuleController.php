<?php

namespace App\Controllers;

use App\Models\CurrRule;
use App\Models\Place;

class CurrRuleController extends Controller
{
    public function details($req, $res, $args)
    {
        return $this->view->render($res, 'curr-rule/details.twig', [
            'place' => Place::byId($args['id']),
            'curr_rules' => CurrRule::byPlace($args['id']),
        ]);
    }
}    
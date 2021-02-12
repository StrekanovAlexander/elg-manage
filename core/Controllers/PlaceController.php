<?php

namespace App\Controllers;

use App\Models\Place;

class PlaceController extends Controller
{
    public function index($req, $res)
    {
        $places = Place::orderBy('full_name', 'ASC')->get();
        return $this->view->render($res, 'place/index.twig', [
            'places' => $places,
        ]);
    }
}  
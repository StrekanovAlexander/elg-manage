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

    public function create($req, $res)
    {
        return $this->view->render($res, 'place/create.twig');
    }

    public function store($req, $res)
    {
        Place::create([
            'full_name' => $req->getParam('full_name'), 
            'sign' => $req->getParam('sign'), 
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
      
        $this->flash->addMessage('message', 'Населенный пункт был успешно создан.');
        return $res->withRedirect($this->router->pathFor('place.index'));
      
    }

    public function edit($req, $res, $args)
    {
        $place = Place::find($args['id']);
        return $this->view->render($res, 'place/edit.twig', [
            'place' => $place,
        ]);
    }

    public function update($req, $res)
    {
        $place = Place::find($req->getParam('id'));
        $place->update([
            'full_name' => $req->getParam('full_name'), 
            'sign' => $req->getParam('sign'), 
            'is_bot_active' => $req->getParam('is_bot_active') ? true : false,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Населенный пункт был отредактирован.');
        return $res->withRedirect($this->router->pathFor('place.index'));
    }
    
}  
<?php

namespace App\Controllers;

use App\Models\Dep;
use App\Models\Place;

class DepController extends Controller
{
    public function index($req, $res)
    {
        $deps = Dep::orderBy('full_name')->get();
        return $this->view->render($res, 'dep/index.twig', [
            'deps' => $deps,
        ]);
    }

    public function create($req, $res)
    {
        $places = Place::orderBy('full_name')->where('is_actual', true)->get();
        return $this->view->render($res, 'dep/create.twig', [
            'places' => $places,
        ]);
    }

    public function store($req, $res)
    {
        Dep::create([
            'full_name' => $req->getParam('full_name'), 
            'place_id' => $req->getParam('place_id'), 
            'email' => $req->getParam('email'),
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
      
        $this->flash->addMessage('message', 'Отделение было успешно создано.');
        return $res->withRedirect($this->router->pathFor('dep.index'));
      
    }

    public function edit($req, $res, $args)
    {
        $dep = Dep::find($args['id']);
        $places = Place::orderBy('full_name')->where('is_actual', true)->get();
        return $this->view->render($res, 'dep/edit.twig', [
            'dep' => $dep,
            'places' => $places,
        ]);
    }

    public function update($req, $res)
    {
        $dep = Dep::find($req->getParam('id'));
        $dep->update([
            'full_name' => $req->getParam('full_name'), 
            'place_id' => $req->getParam('place_id'), 
            'email' => $req->getParam('email'),
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Отделение было отредактировано.');
        return $res->withRedirect($this->router->pathFor('dep.index'));
    }

}  
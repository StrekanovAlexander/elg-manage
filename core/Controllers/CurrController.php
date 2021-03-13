<?php

namespace App\Controllers;

use App\Models\Curr;

class CurrController extends Controller
{

    private static $step_sizes = [
        ['value' => '0.01000'], 
        ['value' => '0.00100']
    ];

    private static $step_size_formats = [
        ['value' => '0.01'], 
        ['value' => '0.001']
    ];

    public function index($req, $res)
    {
        $currs = Curr::orderBy('id')->where('is_main', false)->get();
        return $this->view->render($res, 'curr/index.twig', [
            'currs' => $currs,
        ]);
    }

    public function create($req, $res)
    {
        return $this->view->render($res, 'curr/create.twig', [
            'step_sizes' => self::$step_sizes,
            'step_size_formats' => self::$step_size_formats,
        ]);
    }

    public function store($req, $res)
    {
        $isExists = Curr::isExists($req->getParam('short_name'));
        if ($isExists) {
            $this->flash->addMessage('error', 'Отказ! Попытка создания уже существующей валюты.');
            return $this->response->withRedirect($this->router->pathFor('curr.create'));
        }
    
        Curr::create([
            'short_name' => $req->getParam('short_name'), 
            'sign' => $req->getParam('sign'), 
            'step_size' => $req->getParam('step_size'),
            'step_size_format' => $req->getParam('step_size_format'),
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
      
        $this->flash->addMessage('message', 'Валюта была успешно создана');
        return $res->withRedirect($this->router->pathFor('curr.index'));
      
    }

    public function edit($req, $res, $args)
    {
        $curr = Curr::find($args['id']);
        return $this->view->render($res, 'curr/edit.twig', [
            'curr' => $curr,
            'step_sizes' => self::$step_sizes,
            'step_size_formats' => self::$step_size_formats,
        ]);
    }

    public function update($req, $res)
    {
        $curr = Curr::find($req->getParam('id'));
        $curr->update([
            'short_name' => $req->getParam('short_name'), 
            'sign' => $req->getParam('sign'), 
            'step_size' => $req->getParam('step_size'),
            'step_size_format' => $req->getParam('step_size_format'),
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Валюта была отредактирована');
        return $res->withRedirect($this->router->pathFor('curr.index'));
    }

}  
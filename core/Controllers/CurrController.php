<?php

namespace App\Controllers;

use App\Models\ColorCss;
use App\Models\Curr;

class CurrController extends Controller
{

    private static $step_sizes = [
        ['value' => '0.10000'], 
        ['value' => '0.01000'], 
        ['value' => '0.00100'],
        ['value' => '0.00010'],
        ['value' => '0.00001'],
    ];

    private static $step_size_formats = [
        ['value' => '0.10'], 
        ['value' => '0.01'], 
        ['value' => '0.001'],
        ['value' => '0.0001'],
        ['value' => '0.00001'],
    ];

    public function index($req, $res)
    {
        $colors = ColorCss::orderBy('full_name')->get();
        $currs = Curr::orderBy('id')
            ->where('is_main', false)
            ->where('is_cross', false)
            ->get();
        return $this->view->render($res, 'curr/index.twig', [
            'colors' => $colors,
            'currs' => $currs,
        ]);
    }

    public function create($req, $res)
    {
        $colors = ColorCss::orderBy('full_name')->get();
        return $this->view->render($res, 'curr/create.twig', [
            'colors' => $colors,
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
            'color_id' => $req->getParam('color_id'),
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
        $colors = ColorCss::orderBy('full_name')->get();
        $curr = Curr::find($args['id']);
        return $this->view->render($res, 'curr/edit.twig', [
            'colors' => $colors,
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
            'color_id' => $req->getParam('color_id'),
            'sign' => $req->getParam('sign'), 
            'step_size' => $req->getParam('step_size'),
            'step_size_format' => $req->getParam('step_size_format'),
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Валюта была отредактирована');
        return $res->withRedirect($this->router->pathFor('curr.index'));
    }

    public function indexCross($req, $res)
    {
        $colors = ColorCss::orderBy('full_name')->get();
        $currs = Curr::orderBy('id')
            ->where('is_main', false)
            ->where('is_cross', true)
            ->get();
        return $this->view->render($res, 'curr-cross/index.twig', [
            'colors' => $colors,
            'currs' => $currs,
        ]);
    }

    public function createCross($req, $res)
    {
        $colors = ColorCss::orderBy('full_name')->get();
        $currs = Curr::orderBy('id')
            ->where('is_main', false)
            ->where('is_cross', false)
            ->where('is_actual', true)
            ->get();
        return $this->view->render($res, 'curr-cross/create.twig', [
            'colors' => $colors,
            'currs' => $currs,
            'step_sizes' => self::$step_sizes,
            'step_size_formats' => self::$step_size_formats,
        ]);
    }

    public function storeCross($req, $res)
    {
        $short_name = Curr::crossShortName(
            $req->getParam('base_curr_id'),
            $req->getParam('rel_curr_id')
        );

        Curr::create([
            'short_name' => $short_name, 
            'base_curr_id' => $req->getParam('base_curr_id'), 
            'rel_curr_id' => $req->getParam('rel_curr_id'),
            'color_id' => $req->getParam('color_id'),
            'step_size' => $req->getParam('step_size'),
            'step_size_format' => $req->getParam('step_size_format'),
            'is_cross' => true,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
      
        $this->flash->addMessage('message', 'Валюта (кросс) была успешно создана');
        return $res->withRedirect($this->router->pathFor('curr.cross.index'));
    }

    public function editCross($req, $res, $args)
    {
        $colors = ColorCss::orderBy('full_name')->get();
        $curr = Curr::find($args['id']);
        $currs = Curr::orderBy('id')
            ->where('is_main', false)
            ->where('is_cross', false)
            ->where('is_actual', true)
            ->get();
        return $this->view->render($res, 'curr-cross/edit.twig', [
            'colors' => $colors,
            'curr' => $curr,
            'currs' => $currs,
            'step_sizes' => self::$step_sizes,
            'step_size_formats' => self::$step_size_formats,
        ]);
    }

    public function updateCross($req, $res)
    {
        $short_name = Curr::crossShortName(
            $req->getParam('base_curr_id'),
            $req->getParam('rel_curr_id')
        );
        $curr = Curr::find($req->getParam('id'));
        $curr->update([
            'short_name' => $short_name, 
            'base_curr_id' => $req->getParam('base_curr_id'),
            'rel_curr_id' => $req->getParam('rel_curr_id'),
            'color_id' => $req->getParam('color_id'),
            'step_size' => $req->getParam('step_size'),
            'step_size_format' => $req->getParam('step_size_format'),
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Валюта (кросс) была отредактирована');
        return $res->withRedirect($this->router->pathFor('curr.cross.index'));
    }

}  
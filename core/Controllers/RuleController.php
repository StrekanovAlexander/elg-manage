<?php

namespace App\Controllers;

use App\Models\Place;
use App\Models\Rule;

class RuleController extends Controller
{
    public function details($req, $res, $args)
    {
        return $this->view->render($res, 'rule/details.twig', [
            'place' => Place::byId($args['id']),
            'rules' => Rule::byPlace($args['id']),
        ]);
    }

    public function edit($req, $res, $args)
    {
        return $this->view->render($res, 'rule/edit.twig', [
            'place' => Place::byId($args['id']),
            'rules' => Rule::byPlace($args['id']),
        ]);
    }

    public function update($req, $res)
    {

        $arr = \App\Common\String::cleanParams($req->getParams(), [
            'id',
            'csrf_name', 
            'csrf_value',
        ], 3);

        foreach($arr as $value) {
            $rule = Rule::find($value[0]);
            $rule->update([
                'diff_buy' => $value[1],
                'diff_sale' => $value[2],
            ]);
        }

        $this->flash->addMessage('message', 'Настройки курсов валют успешно записаны');
        return $this->response->withRedirect($this->router->pathFor('rule.details', [
            'id' => $req->getParam('id'), 
        ]));
    }
}    
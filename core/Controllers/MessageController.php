<?php

namespace App\Controllers;

use App\Models\Message;
use App\Common\Emoji;

class MessageController extends Controller
{
    public function index($req, $res)
    {
        $messages = Message::orderBy('position', 'ASC')->get();
        foreach($messages as $message) {
            $message->content = Emoji::decode($message->content);
        }
        return $this->view->render($res, 'message/index.twig', [
            'messages' => $messages,
        ]);
    }

    public function create($req, $res)
    {
        $max_pos = Message::max('position');
        $max_pos += 1;
        
        return $this->view->render($res, 'message/create.twig', [
            'max_pos' => $max_pos,
        ]);
    }

    public function store($req, $res)
    {
        Message::create([
            'content' => Emoji::encode($req->getParam('content')), 
            'prefix' => $req->getParam('prefix'),
            'position' => $req->getParam('position'), 
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
      
        $this->flash->addMessage('message', 'Текстовое сообщение было создано');
        return $res->withRedirect($this->router->pathFor('message.index'));
    }

    public function edit($req, $res, $args)
    {
        $max_pos = Message::max('position');
        $max_pos += 1;
        $message = Message::find($args['id']);
        $message->content = Emoji::decode($message->content);
        return $this->view->render($res, 'message/edit.twig', [
            'message' => $message,
            'prefix' => $message->prefix,
            'position' => $message->position,
            'max_pos' => $max_pos,
        ]);
    }

    public function update($req, $res)
    {
        $message = Message::find($req->getParam('id'));
        $message->update([
            'content' => Emoji::encode($req->getParam('content')), 
            'prefix' => $req->getParam('prefix'),
            'position' => $req->getParam('position'), 
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Текст сообщения был отредактирован');
        return $res->withRedirect($this->router->pathFor('message.index'));
    }
    
}  
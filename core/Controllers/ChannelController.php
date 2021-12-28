<?php

namespace App\Controllers;

use App\Common\Bot;
use App\Common\Emoji;
use App\Models\Channel;
use App\Models\ChannelMessage;
use App\Common\Settings;

class ChannelController extends Controller
{
    public function index($req, $res)
    {
        return $this->view->render($res, 'channel/index.twig', [
          'channels' => Channel::orderBy('full_name')->get(),
        ]);
    }

    public function create($req, $res)
    {
        return $this->view->render($res, 'channel/create.twig');
    }


    public function store($req, $res)
    {
        Channel::create([
            'full_name' => $req->getParam('full_name') ? $req->getParam('full_name') : 'Новый канал'  , 
            'url' => $req->getParam('url') ? $req->getParam('url') : 'URL', 
            'chat_id' => $req->getParam('chat_id') ? $req->getParam('chat_id') : 'chat_id', 
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
      
        $this->flash->addMessage('message', 'Канал был создан');
        return $res->withRedirect($this->router->pathFor('channel.index'));
      
    }

    public function edit($req, $res, $args)
    {
        return $this->view->render($res, 'channel/edit.twig', [
            'channel' => Channel::find($args['id']),
        ]);
    }

    public function update($req, $res)
    {
        $channel = Channel::find($req->getParam('id'));
        $channel->update([
            'full_name' => $req->getParam('full_name'), 
            'url' => $req->getParam('url'), 
            'chat_id' => $req->getParam('chat_id'), 
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
        $this->flash->addMessage('message', 'Канал был отредактирован');
        return $res->withRedirect($this->router->pathFor('channel.index'));
    }

    public function messages($req, $res, $args)
    {
        $channel = Channel::find($args['id']);

        $channel_messages = ChannelMessage::orderBy('position')
            ->where('channel_id', $channel->id)->get();
        
        foreach($channel_messages as $message) {
            $message->content = Emoji::decode($message->content);
        }    

        return $this->view->render($res, 'channel/message/index.twig', [
          'channel' => $channel,  
          'channel_messages' => $channel_messages,
        ]);
    }

    public function createMessage($req, $res, $args)
    {
        $channel = Channel::find($args['id']);
        $max_pos = ChannelMessage::where('channel_id', $channel->id)->max('position');
        $max_pos += 1;

        return $this->view->render($res, 'channel/message/create.twig', [
            'channel' => $channel,
            'max_pos' => $max_pos,
        ]);
    }

    public function storeMessage($req, $res)
    {
        $channel = Channel::find($req->getParam('id'));
        ChannelMessage::create([
            'channel_id' => $channel->id, 
            'content' => Emoji::encode($req->getParam('content')), 
            'position' => $req->getParam('position'), 
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);
  
        $this->flash->addMessage('message', 'Cообщение в канале: ' . $channel->full_name . ' было создано');
        return $res->withRedirect($this->router->pathFor('channel.message.index', [
            'id' => $channel->id,
        ]));

    }

    public function editMessage($req, $res, $args)
    {
        $channel_message = ChannelMessage::find($args['id']);
        $channel = Channel::find($channel_message->channel_id);
        $max_pos = ChannelMessage::where('channel_id', $channel->id)->count('position');
        $channel_message->content = Emoji::decode($channel_message->content);
        return $this->view->render($res, 'channel/message/edit.twig', [
            'channel' => $channel,
            'channel_message' => $channel_message,
            'max_pos' => $max_pos,
        ]);
    }

    public function updateMessage($req, $res)
    {
        $channel_message = ChannelMessage::find($req->getParam('id'));
        $channel = Channel::find($channel_message->channel_id);

        $channel_message->update([
            'content' => Emoji::encode($req->getParam('content')), 
            'position' => $req->getParam('position'), 
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Cообщение в канале: ' . $channel->full_name . ' было отредактировано');
        return $res->withRedirect($this->router->pathFor('channel.message.index', [
            'id' => $channel->id,
        ]));

    }

    public function sendMessages($req, $res) {

        $channels = Channel::where('is_actual', true)->get();
        
        $messages = []; 
        foreach($channels as $key => $value) {
            $channel_messages = ChannelMessage::orderBy('position')
                ->where('channel_id', $value->id)->where('is_actual', true)
                ->get()->toArray();

            $content = array_reduce($channel_messages, function($acc, $el) {
                return $el['content'] ? $acc .= $el['content'] . "\n\n" : null;
            }, '');

            $bot_token = $value->id == 6 ? Settings::$global['main_bot_token'] : Settings::$global['info_bot_token']; 

            if ($content) {
                Bot::sendToChat($bot_token, $value->chat_id, Emoji::decode($content));
                // $messages[] = ['chat_id' => $value->chat_id, 'content' => $content];
            }    
        }

        $this->flash->addMessage('message', 'Сообщения в каналы были отправлены');
        return $this->response->withRedirect($this->router->pathFor('base.index2'));

        // return $this->view->render($res, 'channel/message/debug.twig', [
        //     'channels' => $channels,
        //     'messages' => $messages,
        // ]);
        
    }

}  
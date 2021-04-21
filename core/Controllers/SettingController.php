<?php

namespace App\Controllers;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index($req, $res)
    {
        $settings = Setting::first();
        return $this->view->render($res, 'setting/index.twig', [
            'settings' => $settings,
        ]);
    }

    public function update($req, $res)
    {
        $settings = Setting::find($req->getParam('id'));
        $settings->update([
            'is_chat_alert' => $req->getParam('is_chat_alert') ? true : false,
        ]);

        $this->flash->addMessage('message', 'Настройки были сохранены');
        return $res->withRedirect($this->router->pathFor('setting.index'));
    }
}   
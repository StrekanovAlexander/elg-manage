<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;
use App\Models\Place;
use App\Models\Rate;

class ServiceController extends Controller
{
  
  public function index($req, $res)
  {
    return $this->view->render($res, 'service/index.twig', [
      'baseRatesCount' => BaseRate::count(),
      'baseRateMaxId' => BaseRate::max('id'),
      'ratesCount' => Rate::count(),
      'rateMaxId' => Rate::max('id'),
      'currsCount' => Curr::countActual(),
      'placesCount' => Place::countActual(),
    ]);
  }

  public function getClear($req, $res)
  {
    $limit = self::getClearLimit();

    $baseRatesCount = BaseRate::count();
    $ratesCount = Rate::count();

    return $this->view->render($res, 'service/clear.twig', [
      'baseRatesCount' =>  $baseRatesCount < $limit ? 0 :  $baseRatesCount - $limit,
      'ratesCount' =>  $ratesCount < $limit ? 0 :  $ratesCount - $limit,
    ]);
    
  }

  public function postClear($req, $res, $args)
  {
    
    $limit = self::getClearLimit();
    $baseRateMaxId = BaseRate::max('id') - $limit;
    $rateMaxId = Rate::max('id') - $limit;

    BaseRate::where('id', '<=', $baseRateMaxId)->delete();
    Rate::where('id', '<=', $rateMaxId)->delete();

    $this->flash->addMessage('message', 'Данные очищены');
    
    return $this->response->withRedirect($this->router->pathFor('service.index'));
    
  }

  private static function getClearLimit()
  {
    return  Curr::countActual() * Place::countActual() * 3;
  }

}  
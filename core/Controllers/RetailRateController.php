<?php

namespace App\Controllers;

use App\Models\BaseRate;
use App\Models\Curr;
use App\Models\Place;
use App\Models\Rule;

class RetailRateController extends Controller
{

    public function index($req, $res)
    {
      $base_rates = BaseRate::actual();

      $currs = Curr::orderBy('id')
        ->where('is_actual', true)
        ->where('is_main', false)
        ->get();  

      $places = Place::orderBy('is_base', 'DESC')
        ->orderBy('full_name', 'ASC')
        ->where('is_actual', true)
        ->get(); 
        
      $rules = Rule::where('is_actual', true)->get();  

      return $this->view->render($res, 'retailrate/index.twig', [
        'base_rates' => $base_rates,
        'currs' => $currs,
        'places' => $places,
        'rules' => $rules,
      ]);

    }

    public function storeRetailRateRules($req, $res) 
    {
        $buy_field = 'diff_retail_buy';
        $sale_field = 'diff_retail_sale'; 

        $currs = Curr::orderBy('id')
          ->where('is_actual', true)
          ->where('is_main', false)
          ->get();

        $places = Place::orderBy('is_base', 'DESC')
          ->orderBy('full_name', 'ASC')
          ->where('is_actual', true)
          ->get(); 

        foreach($currs as $curr) {
          foreach($places as $place) {
            $rule = Rule::where('place_id', $place->id)
              ->where('curr_id', $curr->id)
              ->first();
                
            $rule->update([
              $buy_field => $req->getParam($buy_field . '_' . $curr->id . '_' . $place->id),
              $sale_field => $req->getParam($sale_field . '_' . $curr->id . '_' . $place->id),
            ]);
          }    
        }    
    
        $this->flash->addMessage('message', 'Данные по розничным курсам были записаны');
        return $this->response->withRedirect($this->router->pathFor('retailrate.index'));

    }
  
} 
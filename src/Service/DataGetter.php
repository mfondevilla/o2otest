<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataGetter
 *
 * @author Melania
 */

namespace App\Service;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Beer;

class DataGetter {
    
    public function getData($param, $value){
        
        $this->beer = new Beer();
        $client = new \GuzzleHttp\Client();
        $request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.punkapi.com/v2/beers?'. $param .'='. $value);     
        
        $promise = $client->sendAsync($request)->then(function (ResponseInterface $response) {
          
            return $response->getBody()->getContents();
        });
      
        return  $promise->wait(); 
       
      
    }
    
    
}

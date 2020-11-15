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
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Beer;

class DataGetter {
    private $client;
    
    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }
    
    
    public function getData($param, $value){
        
        $this->beer = new Beer();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.punkapi.com/v2/beers?'. $param .'='. $value);    
        return $response->getBody()->getContents();
     
    }
    /*
     * //sin guzzle
    public function getData($param, $value){        
        $response = $this->client->request('GET', 'https://api.punkapi.com/v2/beers?'. $param .'='. $value);
        return  $response->getContent();
    }
    
    */
}

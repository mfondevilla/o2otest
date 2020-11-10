<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use App\Entity\Beer;

class BeerController extends AbstractController
{
      private $id;
    /**
     * @Route("/beer", name="beer")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BeerController.php',
        ]);
    }
    
   
    public function getData($param, $value){
        $this->beer = new Beer();
        $client = new \GuzzleHttp\Client();
        $request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.punkapi.com/v2/beers', [
            'query' => [$param => $value]
        ]);       
        $promise = $client->sendAsync($request)->then(function (ResponseInterface $response) {;
            return $response->getBody()->getContents();
        });
        return   $promise->wait();
   
      
    }
 /* $response = $this->client->request('GET', 'https://api.punkapi.com/v2/beers/random');
   
        dump($response);
        return $response;*/
    //}
    
    /**    
     *  @Rest\Get("/search/{food}", name="beer")
     *  @return array|\FOS\RestBundle\View\View
     */
    public function searchByFood(Request $request)
   {
        $food = $request->get('food');
        $beers = [];
        $allData = json_decode($this->getData('food', $food));
        foreach($allData as $data){
        $beer = new Beer();
            $beer->setDescription($data->description);
            $beer->setId($data->id);
            $beer->setName($data->name);
            array_push($beers, $beer);
        }
        
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $final = $serializer->serialize($beers, 'json');
        return new Response($final, 200);
    }
    
      /**    
     *  @Rest\Get("/detail/{id}", name="beer")
     *  @return array|\FOS\RestBundle\View\View
     */
    public function searchById(Request $request)
   {
        $id = $request->get('id');
        $data = json_decode($this->getData('id',$id))[0];
        $beer = new Beer();
        $beer->setDescription($data->description);
        $beer->setId($data->id);
        $beer->setName($data->name);
        $beer->setImg($data->image_url);
        $beer->setSlogan($data->tagline);
        $beer->setFirstbrewed($data->brewers_tips);
       
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $final = $serializer->serialize($beer, 'json');
        return new Response($final, 200);
    }
}

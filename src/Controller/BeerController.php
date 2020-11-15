<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Psr\Http\Message\ResponseInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Beer;
use App\Service\DataGetter;

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
       
   
    /**    
     *  @Rest\Get("/search/{food}", name="beer")
     *  @return array|\FOS\RestBundle\View\View
     */
    public function searchByFood(Request $request, DataGetter $dataGetter)
   {
        $food = $request->get('food');
        $beers = [];
        $allData = json_decode($dataGetter->getData('food', $food));
        foreach($allData as $data){
        $beer = new Beer();
            $beer->setDescription($data->description);
            $beer->setId($data->id);
            $beer->setName($data->name);
            array_push($beers, $beer);
        }
        
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $final = $serializer->serialize($beers, 'json');
        return new Response($final, 200);
    }
    
      /**    
     *  @Rest\Get("/detail/{id}", name="beer")
     *  @return array|\FOS\RestBundle\View\View
     */
    public function searchById(Request $request,DataGetter $dataGetter)
   {
        $id = $request->get('ids');
        $data = json_decode($dataGetter->getData('ids',$id))[0];
        $beer = new Beer();
        $beer->setDescription($data->description);
        $beer->setId($data->id);
        $beer->setName($data->name);
        $beer->setImg($data->image_url);
        $beer->setSlogan($data->tagline);
        $beer->setFirstbrewed($data->brewers_tips);
       
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $final = $serializer->serialize($beer, 'json');
        return new Response($final, 200);
    }
}

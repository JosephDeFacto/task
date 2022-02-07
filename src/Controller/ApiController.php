<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ApiController
 */
class ApiController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client = null)
    {
        $this->client = $client;
    }

    /**
     * Lists Of All Properties
     * @Route("/api", name="api_index")
     * @return Response
     */
    public function index(): Response
    {
        $response = $this->client->request(
            'GET',
                'https://api.my-rent.net/objects/get_all/12146'
        );

        $content = $response->getContent();
        $content = $response->toArray();

        return $this->render('api/index.html.twig', ['content' => $content]);
    }

    /**
     * Call List
     * @Route("/api/post", name="api_post")
     * @throws TransportExceptionInterface
     */
    public function postSearchRequestByName(Request $request): Response
    {

        $request = $request->request->get('search');
        $url = 'https://api.my-rent.net/objects/free_stock';

        $data = ["user_id" => 332, "name" => $request];
        $data = json_encode($data);

        /*$data = '
        {
            "user_id": "332",
            
            "name": "Apa"
        }
        ';*/

        $additional_headers = ['Content-Type: application/json'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $additional_headers);

        $jsonResponse = curl_exec($curl);
        $result = json_decode($jsonResponse, true);

        return $this->render('api/post.html.twig', [
            'result' => $result,
            'data' => $data,
        ]);

    }

    /**
     * @Route("api/search", name="search")
     * @param Request $request
     * @return Response
     */
    // unneccessary method
    /*public function search(Request $request): Response
    {

        $request = $request->request->get('search');

        $url = 'https://api.my-rent.net/objects/free_stock';

        $data = ["user_id" => 332, "name" => $request];
        $data = json_encode($data);

        $additional_headers = ['Content-Type: application/json'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $additional_headers);

        $jsonResponse = curl_exec($curl);
        $result = json_decode($jsonResponse, true);

        return $this->render('api/post.html.twig', [
            'result' => $result,
            'data' => $data
        ]);
    }*/

    /**
     * @param Request $request
     * @Route("/api/view/{id}", name="api_view")
     * @return Response
     */
    public function getDetails(Request $request): Response
    {
        $id = $request->attributes->get('id');

        $url = 'http://api.my-rent.net/objects/get/' . $id;

        $json_data = file_get_contents($url);

        $response = json_decode($json_data, true);

        return $this->render('api/view.html.twig', [
            'data' => $response
        ]);
    }
}

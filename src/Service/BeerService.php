<?php
/**
 * Created by PhpStorm.
 * User: chica
 * Date: 18/03/2021
 * Time: 19:40
 */

namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BeerService
{
    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * BeerService constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->baseUrl = 'https://api.punkapi.com/v2/beers';
    }

    /**
     * @param array $params
     * @param array $arrayFieldsKeys
     * @return JsonResponse
     */
    public function searchBeer(array $params, array $arrayFieldsKeys)
    {
        $apiResult = $this->get($params);
        try {
            $response = new JsonResponse('[]', $apiResult->getStatusCode());
            if ($apiResult->getStatusCode() === Response::HTTP_OK) {
                $content = json_decode($apiResult->getContent(), true);
                if (!empty($content) && !empty($arrayFieldsKeys)) {
                    $parsedContent = [];
                    foreach ($content as $item) {
                        $intersect = array_intersect_key($item, array_flip($arrayFieldsKeys)
                        );
                        if (!empty($intersect)) {
                            $parsedContent[] = $intersect;
                        }
                    }
                    $response = new JsonResponse(
                        json_encode($parsedContent),
                        Response::HTTP_OK,
                        [],
                        true
                    );
                }
            }

        } catch (
        ClientExceptionInterface |
        RedirectionExceptionInterface |
        ServerExceptionInterface |
        TransportExceptionInterface $e
        ) {
            $response = new JsonResponse('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $response;
    }

    /**
     * @param $params
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     */
    private function get($params)
    {
        $response = new Response('');
        $url = !empty($params) ? $this->baseUrl . '?' . http_build_query($params) : $this->baseUrl;
        try {
            $response = $this->client->request(
                'GET',
                $url
            );
        } catch (TransportExceptionInterface $e) {

        }
        return $response;
    }
}
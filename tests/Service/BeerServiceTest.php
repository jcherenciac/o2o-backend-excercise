<?php

namespace App\Tests\Service;

use App\Service\BeerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

class BeerServiceTest extends TestCase
{
    public function testsearchBeerWouldReturnOkResponseCodeWithoutData(): void
    {
        $mockHttpClient = new MockHttpClient(new MockResponse());
        $beerService = new BeerService($mockHttpClient);
        $response = $beerService->searchBeer([], []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('"[]"', $response->getContent());
    }

    public function testsearchBeerWouldReturnOkResponseCodeWithData(): void
    {
        $expectedResponse = '[{"id":1}]';
        $body = json_encode([['id' => 1]]);
        $mockHttpClient = new MockHttpClient(new MockResponse($body));
        $beerService = new BeerService($mockHttpClient);
        $response = $beerService->searchBeer([], ['id']);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}

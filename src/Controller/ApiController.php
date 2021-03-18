<?php

namespace App\Controller;

use App\Service\BeerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ApiController
 * @package App\Controller
 * @Route(path="/api/")
 */
class ApiController extends AbstractController
{
    private $beerService;

    /**
     * ApiController constructor.
     * @param BeerService $beerService
     */
    public function __construct(BeerService $beerService)
    {
        $this->beerService = $beerService;
    }

    /**
     * @Route("beer", name="beer", methods={"GET"} )
     * @param Request $request
     * @return JsonResponse
     */
    public function searchBeer(Request $request): JsonResponse
    {
        $params = $request->query->all();
        $arrayFieldsKeys = ['id', 'name', 'description'];
        return $this->beerService->searchBeer($params, $arrayFieldsKeys);
    }

    /**
     * @Route("beer/view", name="view_beer", methods={"GET"} )
     * @param Request $request
     * @return JsonResponse
     */
    public function searchBeerViewData(Request $request): JsonResponse
    {
        $params = $request->query->all();
        $arrayFieldsKeys = ['id', 'name', 'description', 'image_url', 'tagline', 'first_brewed
        '];
        return $this->beerService->searchBeer($params, $arrayFieldsKeys);
    }
}

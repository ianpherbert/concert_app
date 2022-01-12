<?php

namespace App\Controller;

use App\Repository\BandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api", name="api.")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/bands/")
     * @Route("/bands/{term}", name="bands")
     */
    public function getAllBands(BandRepository $bandRepository, $term = null): Response
    {
        $items = $bandRepository->getAllBands($term);
        return new Response(json_encode($items));
    }
}

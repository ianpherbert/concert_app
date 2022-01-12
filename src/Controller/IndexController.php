<?php

namespace App\Controller;

use App\Repository\ConcertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ConcertRepository $concertRepository): Response
    {
        $concerts = $concertRepository->getTodayConcerts(10);

        return $this->render('index/index.html.twig', [
            'concerts' => $concerts
        ]);
    }
}

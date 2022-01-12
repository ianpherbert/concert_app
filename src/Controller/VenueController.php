<?php

namespace App\Controller;

use App\Entity\Venue;
use App\Repository\VenueRepository;
use App\Repository\ConcertRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/salle", name="venue.")
 */
class VenueController extends AbstractController
{
    /**
     * @Route("/info/{id}", name="info")
     */
    public function index(int $id, ConcertRepository $concertRepository, VenueRepository $venueRepository): Response
    {
        $venue = $venueRepository->findOneBy(['id' => $id]);
        $concerts = $concertRepository->getNextConcertsByVenue($id, 10);

        return $this->render('venue/index.html.twig', [
            'venue' => $venue,
            'concerts' => $concerts
        ]);
    }
}

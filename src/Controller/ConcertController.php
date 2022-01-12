<?php

namespace App\Controller;

use App\Repository\BillRepository;
use App\Repository\VenueRepository;
use App\Repository\ConcertRepository;
use App\Services\Translator;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TypeError;

/**
 * @Route("/concerts", name="concerts.")
 */
class ConcertController extends AbstractController
{

    /**
     * @var VenueRepository
     */
    private $venueRepository;

    /**
     * @var ConcertRepository
     */
    private $concertRepository;

    /**
     * @var BillRepository
     */
    private $billRepository;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(VenueRepository $venueRepository, ConcertRepository $concertRepository, BillRepository $billRepository, Translator $translator)
    {
        $this->venueRepository = $venueRepository;
        $this->concertRepository = $concertRepository;
        $this->billRepository = $billRepository;
        $this->translator = $translator;
    }


    /**
     * @Route("/all", name="index")
     * @Route("all/{month}", name="index.month")
     * @Route("all/{month}/{year}", name="index.full")
     */
    public function concertsByMonth(Request $request): Response
    {

        $attributes = $request->attributes;
        $month = $attributes->get('month') ? $attributes->get('month') : date("m");
        $year = $attributes->get('year') ? $attributes->get('year') : date("Y");




        try {
            $date = $this->translator->getMonth($month) . " " . $year;
            if ($date == null) {
                return $this->redirectToRoute('concerts.index');
            }
        } catch (TypeError $e) {
            return $this->redirectToRoute('concerts.index');
        }

        $concerts = $this->concertRepository->getConcertsByMonth($month, $year);

        $prev = [
            'm' => $month == 1 ? 12 : $month - 1,
            'y' => $month == 1 ? $year - 1 : $year,
            'string' => $this->translator->getMonth($month == 1 ? 12 : $month - 1)
        ];
        $next = [
            'm' => $month == 12 ? 1 : $month + 1,
            'y' => $month == 12 ? $year + 1 : $year,
            'string' => $this->translator->getMonth($month == 12 ? 1 : $month + 1)
        ];
        return $this->render('concert/index.html.twig', [
            'concerts' => $concerts,
            'dateString' => $date,
            'prev' => $prev,
            'next' => $next
        ]);
    }

    /**
     * @Route("/details/{id}", name="concert")
     */
    public function oneConcert($id): Response
    {

        $concertInfo = $this->concertRepository->getConcert($id);
        $bill = $this->billRepository->getBandsOnBillFull($id);
        $upcoming = $this->concertRepository->getNextConcertsByVenue($concertInfo['venue_id'], 5, $id);


        return $this->render('concert/concert.html.twig', [
            'concert' => $concertInfo,
            'bands' => $bill,
            'upcoming' => $upcoming
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\BandRepository;
use App\Repository\BillRepository;
use App\Repository\ConcertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BandController extends AbstractController
{
    /**
     * @Route("/band/{id}", name="band")
     */
    public function index($id, BandRepository $bandRepository, BillRepository $billRepository): Response
    {

        $band = $bandRepository->findOneBy(['id' => $id]);
        $concerts = $billRepository->getConcertsFromBill($id);

        return $this->render('band/index.html.twig', [
            'band' => $band,
            'concerts' => $concerts,
            'private' => $band->getIsPrivate()
        ]);
    }
}

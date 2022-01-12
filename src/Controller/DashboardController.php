<?php

namespace App\Controller;

use Exception;
use App\Entity\Band;
use App\Entity\Bill;
use App\Entity\Concert;
use App\Form\ConcertType;
use App\Services\FileUploader;
use App\Repository\BandRepository;
use App\Repository\BillRepository;
use App\Repository\VenueRepository;
use App\Repository\ConcertRepository;
use CurrentUser;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/dashboard", name="dashboard.")
 */
class DashboardController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var BandRepository
     */
    private $bandRepository;


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
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var CurrentUser
     */
    private $currentUser;


    public function __construct(Security $security, VenueRepository $venueRepository, BandRepository $bandRepository, BillRepository $billRepository, ConcertRepository $concertRepository, ManagerRegistry $managerRegistry)
    {
        $this->security = $security;
        $this->billRepository = $billRepository;
        $this->bandRepository = $bandRepository;
        $this->venueRepository = $venueRepository;
        $this->concertRepository = $concertRepository;
        $this->managerRegistry = $managerRegistry;

        $user = $this->security->getUser();
        $this->currentUser =  new CurrentUser($user->getId(), $user->getRoles(), $bandRepository, $venueRepository);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $entity = $this->currentUser->getEntity();
        switch ($this->currentUser->getRole()) {
            case 'band':
                return $this->bandDashboard();
                break;
            case 'venue':
                return $this->venueDashboard();
                break;
            default:
                return $this->redirectToRoute('index');
        }
    }


    private function bandDashboard()
    {
        $band = $this->currentUser->getEntity();
        $concerts = $this->billRepository->getConcertsFromBill($band->getId());

        return $this->render('dashboard/band_dashboard.html.twig', [
            'data' => $band,
            'concerts' => $concerts
        ]);
    }

    private function venueDashboard()
    {
        $id = $this->currentUser->getEntityId();


        $venue = $this->venueRepository->findOneBy(['id' => $id]);
        $concerts = $this->concertRepository->getNextConcertsByVenue($id, 5);

        return $this->render('dashboard/venue_dashboard.html.twig', [
            'info' => $venue,
            'concerts' => $concerts
        ]);
    }


    /**
     * @Route("/compte", name="account")
     */
    public function account()
    {
        return $this->render('dashboard/account.html.twig');
    }

    /**
     * @Route("/updateInfo", name="updateInfo")
     */
    public function updateInfo(Request $request)
    {

        $em = $this->managerRegistry->getManager();

        switch ($this->currentUser->getRole()) {
            case 'band':
                $band = $this->currentUser->getEntity();
                $form = $this->createFormBuilder($band)
                    ->add("name")
                    ->add('city')
                    ->add('region')
                    ->add('country')
                    ->add('bio')
                    ->add('enregistrer', SubmitType::class)
                    ->getForm();

                $form->handleRequest($request);

                if ($form->isSubmitted()) {

                    $em = $this->managerRegistry->getManager();
                    if (!$band) {
                        throw $this->createNotFoundException(
                            'No band found for id '
                        );
                    }
                    $formData = $form->getData();

                    $band->setName($formData->getName());
                    $band->setCity($formData->getCity());
                    $band->setRegion($formData->getRegion());
                    $band->setCountry($formData->getCountry());
                    $band->setBio($formData->getBio());
                    $em->flush();
                    $this->addFlash('success', 'Votre profil a été mis à jour');
                    return $this->redirectToRoute('dashboard.home');
                }
                return $this->render('dashboard/updateInfo.html.twig', ['form' => $form->createView()]);
                break;
            case 'venue':
                $venue = $this->currentUser->getEntity();

                $form = $this->createFormBuilder($venue)
                    ->add("name")
                    ->add('address')
                    ->add('city')
                    ->add('region')
                    ->add('country')
                    ->add('postal_code')
                    ->add('capacity', NumberType::class)
                    ->add('enregistrer', SubmitType::class)
                    ->getForm();

                $form->handleRequest($request);

                if ($form->isSubmitted()) {
                    $em = $this->managerRegistry->getManager();


                    if (!$venue) {
                        throw $this->createNotFoundException(
                            'No band found for id '
                        );
                    }
                    $formData = $form->getData();

                    $venue->setName($formData->getName());
                    $venue->setAddress($formData->getAddress());
                    $venue->setCity($formData->getCity());
                    $venue->setRegion($formData->getRegion());
                    $venue->setCountry($formData->getCountry());
                    $venue->setPostalCode($formData->getPostalCode());
                    $venue->setCapacity($formData->getCapacity());
                    $em->flush();
                    $this->addFlash('success', 'Votre profil a été mis à jour');
                    return $this->redirectToRoute('dashboard.home');
                }


                return $this->render('dashboard/updateInfo.html.twig', ['form' => $form->createView()]);
                break;
            default:
                return $this->redirectToRoute('index');
        }
    }

    /**
     * @Route("/privacy/{action}", name="privacy")
     */
    public function togglePrivacy($action)
    {
        $em = $this->managerRegistry->getManager();
        $this->currentUser->getEntity()->setIsPrivate($action);
        $em->flush();
        if ($action) {
            $this->addFlash('success', "Votre profile est maintenent privé");
        } else {
            $this->addFlash('success', "Votre profile est maintenent publique");
        }
        return $this->redirectToRoute('dashboard.home');
    }

    /**
     * @Route("/changePhoto/", name="changePhoto")
     */
    public function changePhoto(Request $request, FileUploader $fileUploader)
    {
        $form = $this->createFormBuilder()
            ->add('photo', FileType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var UploadedFile $file */
            $file = $request->files->get('form')['photo'];

            if ($file) {
                $filename = $fileUploader->uploadFile($file);
                $em = $this->managerRegistry->getManager();
                $this->currentUser->getEntity()->setPhoto($filename);
                $em->flush();
            }

            $this->addFlash('success', "Votre photo a été changé");
            return $this->redirectToRoute('dashboard.home');
        }
        return $this->render("dashboard/change_photo.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/concerts", name="concertList")
     */
    public function showAllConcerts()
    {

        $id = $this->currentUser->getEntityId();
        switch ($this->currentUser->getRole()) {
            case 'band':
                $concerts = $this->billRepository->getConcertsFromBill($id);
                break;
            case 'venue':
                $concerts = $this->concertRepository->getNextConcertsByVenue($id, 0);
                break;
            default:
                $this->redirectToRoute('dashboard.home');
        }
        return $this->render("dashboard/concerts.html.twig", [
            'concerts' => $concerts,
        ]);
    }

    /**
     * @Route("/concert/{action}", name="concert")
     */
    public function createConcert(Request $request, $action)
    {
        if ($this->currentUser->getRole() != 'venue') {
            $this->redirectToRoute('index');
        }
        try {
            $em = $this->managerRegistry->getManager();
            if ($action == "create") {
                $concert = new Concert();
                $concert->setVenue($this->currentUser->getEntity());
            } else {
                $concert = $this->concertRepository->findOneBy(['id' => $action]);
                if (is_null($concert) || $concert->getVenue()->getId() != $this->currentUser->getEntityId()) {
                    throw new Exception("Error Processing Request", 1);
                }
            }
        } catch (Exception $e) {
            return $this->redirectToRoute('index');
        }
        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($concert);
            $em->flush();

            if ($action == "create") {
                $this->addFlash('success', "Votre concert, " . $concert->getName() . " a été créé");
                return $this->redirectToRoute('dashboard.home');
            } else {
                $this->addFlash('success', "Votre concert, " . $concert->getName() . " a été modifié");
                return $this->redirectToRoute('dashboard.concertList', ['entity' => 'salle']);
            }
        }

        return $this->render("dashboard/create_concert.html.twig", [
            'form' => $form->createView(),
            'action' => ($action == 'create' ? 'Créer ' : "Modifier ")
        ]);
    }



    /**
     * @Route("/modifyconcert/{concertId}", name="modifyBill")
     */
    public function modifyBill($concertId, Request $request)
    {
        $concert = $this->concertRepository->findOneBy(['id' => $concertId]);

        if ($concert->getVenue()->getId() != $this->currentUser->getEntityId()) {
            return $this->redirectToRoute('index');
        }


        $bill = new Bill();
        $bill->setConcert($concert);

        $form = $this->createFormBuilder($bill)
            ->add('band', EntityType::class, [
                'class' => Band::class,
                'label' => "Groupe à ajouter:"
            ])
            ->add('Ajouter', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->managerRegistry->getManager();
            $em->persist($bill);
            $em->flush();
            $this->addFlash('add', $bill->getBand() . " a été ajouté à " . $bill->getConcert());
        }
        $bands = $this->billRepository->getBandsOnBill($concertId);

        return $this->render(
            'dashboard/modify_bill.html.twig',
            [
                'form' => $form->createView(),
                'bands' => $bands,
                'concert' => $concert
            ]
        );
    }

    /**
     * @Route("/deletebill/{billId}", name="deleteBill")
     */
    public function deleteBill($billId)
    {
        $bill = $this->billRepository->findOneBy(['id' => $billId]);
        $concertId = $bill->getConcert()->getId();

        if ($this->concertRepository->findOneBy(['id' => $concertId])->getVenue()->getId() != $this->currentUser->getEntityId() || $this->currentUser->getRole() != 'venue') {
            return $this->redirectToRoute('index');
        }


        $em = $this->managerRegistry->getManager();
        $em->remove($bill);
        $em->flush();
        return $this->redirectToRoute('dashboard.modifyBill', ['concertId' => $concertId]);
    }
}

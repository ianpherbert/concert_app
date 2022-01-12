<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\User;
use App\Entity\Venue;
use App\Repository\BandRepository;
use App\Repository\UserRepository;
use App\Repository\VenueRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\EntityManagerClosed;

use function PHPSTORM_META\type;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/registration", name="registration.")
 */
class RegistrationController extends AbstractController
{


    private $bandRepository;

    private $venueRepository;

    public function __construct(BandRepository $bandRepository, VenueRepository $venueRepository)
    {
        $this->bandRepository = $bandRepository;
        $this->venueRepository = $venueRepository;
    }




    /**
     * @Route("/", name="registration")
     */
    public function index(Request $request, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createFormBuilder()
            ->add('username', null, ['label' => "Nom d'utilisateur"])
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => ['label' => 'Mot de Passe'],
                'second_options' => ['label' => 'Verifier mot de passe'],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Groupe' => 'band',
                    'Salle de Spectacle' => 'venue'
                ]
            ])
            // ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {

                $data = $form->getData();
                $user = new User;
                $user->setUsername($data['username']);
                $user->setEmail($data['email']);
                $user->setPassword($hasher->hashPassword($user, $data['password']));
                $user->setRoles([$data['role']]);
                $em = $managerRegistry->getManager();
                $em->persist($user);
                $em->flush();

                switch ($data['role']) {
                    case 'band':
                        $entity = new Band();
                        break;
                    case 'venue':
                        $entity = new Venue();
                        break;
                }

                $entity->setUser($user);
                $em->persist($entity);
                $em->flush();
                $userHash = hash('md5', $user->getId());
                $entityHash = hash('md5', $entity->getId());

                setcookie('user', $user->getUserIdentifier());
                setcookie('u_sec', $userHash);
                setcookie('e_sec', $entityHash);

                return $this->redirectToRoute('registration.create');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash("err", "Le nom d'utilisateur: " . $form->getData()['username'] . " existe déjà.");
            } catch (EntityManagerClosed $e) {
                $this->addFlash("err", "Le nom d'utilisateur: " . $form->getData()['username'] . " existe déjà.");
            }
        }


        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/create/", name="create")
     */
    public function createProfile(UserRepository $userRepository, Request $request, ManagerRegistry $managerRegistry)
    {
        $user = $_COOKIE['user'];
        $userHash = $_COOKIE['u_sec'];




        $userEntity = $userRepository->findOneBy(['username' => $user]);

        if ($userHash != hash('md5', $userEntity->getId())) {
            $this->redirectToRoute('index');
            die();
        }


        if (in_array('venue', $userEntity->getRoles())) {
            $role = 'venue';
        } else if (in_array('band', $userEntity->getRoles())) {
            $role = 'band';
        }

        switch ($role) {
            case 'venue':
                $form = $this->venueForm($userEntity);
                break;
            case 'band':
                $form = $this->bandForm($userEntity);
                break;
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $formData = $form->getData();


            switch ($role) {
                case 'venue':
                    $entity = $this->updateVenue($formData);
                    break;
                case 'band':
                    $entity = $this->updateBand($formData);
                    break;
            }
            $em = $managerRegistry->getManager();
            $em->persist($entity);
            $em->flush();


            setcookie('user', null, time() - 1000);
            setcookie('u_sec', null, time() - 1000);
            setcookie('e_sec', null, time() - 1000);


            $this->addFlash('created', 'Votre compte a été créé !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function bandForm($userId)
    {

        $entityHash = $_COOKIE['e_sec'];
        $band = $this->bandRepository->findOneBy(['user' => $userId]);

        if (hash('md5', $band->getId()) != $entityHash) {
            dd('non');
        }

        $form = $this->createFormBuilder($band)
            ->add('name', null, ['label' => 'Quel est le nom de votre groupe ?'])
            ->add('city', null, ['label' => 'Elle se trouve dans quelle ville ?'])
            ->add('region', null, ['required' => false, 'label' => "C'est quel région ?"])
            ->add('country', null, ['label' => "Je suis null en géographie, c'est quel pays déja ?"])
            ->add('photo', FileType::class, ['required' => false, 'label' => "Ajouter un photo de votre groupe"])
            ->add('bio', TextareaType::class, ['required' => false, 'label' => "Raccontez-nous un peu sur votre histoire"])
            ->add('submit', SubmitType::class, ['label' => "C'a me semble correcte"])
            ->getForm();


        return $form;
    }

    private function venueForm($userId)
    {
        $entityHash = $_COOKIE['e_sec'];
        $venue = $this->venueRepository->findOneBy(['user' => $userId]);
        if (hash('md5', $venue->getId()) != $entityHash) {
            dd('non');
        }

        $form = $this->createFormBuilder($venue)
            ->add('name', null, ['label' => 'Quel est le nom de votre salle de concert ?'])
            ->add('address', null, ['label' => "Quel est votre adresse ?"])
            ->add('city', null, ['label' => 'Elle se trouve dans quelle ville ?'])
            ->add('region', null, ['required' => false, 'label' => "C'est quel région ?"])
            ->add('country', null, ['label' => "Je suis null en géographie, c'est quel pays déja ?"])
            ->add('postal_code', null, ['label' => "et le code postale ?"])
            ->add('photo', FileType::class, ['required' => false, 'label' => "Ajouter un photo de votre salle de concert"])
            ->add('photo', FileType::class, ['required' => false])
            ->add('submit', SubmitType::class)
            ->getForm();

        return $form;
    }

    private function updateBand($formData)
    {

        $entityHash = $_COOKIE['e_sec'];
        $band = $this->bandRepository->findOneBy(['id' => $formData->getId()]);

        if (hash('md5', $band->getId()) != $entityHash) {
            dd('non');
        }



        $band->setName($formData->getName());
        $band->setCity($formData->getCity());
        $band->setRegion($formData->getRegion());
        $band->setCountry($formData->getCountry());
        $band->setBio($formData->getBio());


        return $band;
    }

    private function updateVenue($formData)
    {
        $entityHash = $_COOKIE['e_sec'];
        $venue = $this->venueRepository->findOneBy(['id' => $formData->getId()]);
        if (hash('md5', $venue->getId()) != $entityHash) {
            dd('non');
        }

        $venue->setName($formData->getName());
        $venue->setAddress($formData->getAddress());
        $venue->setCity($formData->getCity());
        $venue->setRegion($formData->getRegion());
        $venue->setCountry($formData->getCountry());
        $venue->setPostalCode($formData->getPostalCode());
        $venue->setCapacity($formData->getCapacity());

        return $venue;
    }
}

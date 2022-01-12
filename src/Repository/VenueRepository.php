<?php

namespace App\Repository;

use App\Entity\Venue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Venue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Venue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Venue[]    findAll()
 * @method Venue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Venue::class);
    }


    public function findOneVenue(int $id)
    {
        $qb = $this->createQueryBuilder("v");

        $qb->select('v.name')
            ->addSelect('v.city')
            ->addSelect('v.region')
            ->addSelect('v.country')
            ->addSelect('v.address')
            ->addSelect('v.photo')
            ->addSelect('v.isPrivate')
            ->addSelect('v.postal_code')
            ->addSelect('v.capacity')
            ->addSelect('v.id')
            ->where("v.id = :id")
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}

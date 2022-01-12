<?php

namespace App\Repository;

use App\Entity\Band;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Band|null find($id, $lockMode = null, $lockVersion = null)
 * @method Band|null findOneBy(array $criteria, array $orderBy = null)
 * @method Band[]    findAll()
 * @method Band[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Band::class);
    }

    public function findOneBand(int $id)
    {
        $qb = $this->createQueryBuilder("b");

        $qb->select('b.name')
            ->addSelect('b.city')
            ->addSelect('b.region')
            ->addSelect('b.country')
            ->addSelect('b.bio')
            ->addSelect('b.photo')
            ->addSelect('b.isPrivate')
            ->addSelect('b.id')
            ->where("b.user = :id")
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getAllBands($term)
    {
        $qb = $this->createQueryBuilder('b');

        $qb->select('b.name')
            ->addSelect('b.city')
            ->addSelect('b.id');
        if ($term != null) {
            $qb->where($qb->expr()->like('b.name', ':name'))
                ->setParameter('name', "%" . $term . "%");
        }

        $qb->orderBy('b.name');

        return $qb->getQuery()->getResult();
    }
}

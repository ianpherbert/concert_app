<?php

namespace App\Repository;

use App\Entity\Bill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bill[]    findAll()
 * @method Bill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bill::class);
    }

    public function getBandsOnBill($concertId)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('c.name')
            ->addSelect('b.id bill')
            ->innerJoin('b.band', 'c')
            ->where("b.concert = :concert")
            ->setParameter(':concert', $concertId);

        return $qb->getQuery()->getResult();
    }

    public function getBandsOnBillFull($concertId)
    {
        $qb = $this->createQueryBuilder('bill');
        $qb->select('band.name')
            ->addSelect('band.photo')
            ->addSelect('band.isPrivate')
            ->addSelect('band.id')
            ->innerJoin('bill.band', 'band')
            ->where("bill.concert = :concert")
            ->setParameter(':concert', $concertId);

        return $qb->getQuery()->getResult();
    }

    public function getConcertsFromBill($bandId)
    {

        $qb = $this->createQueryBuilder('b');

        $qb->select('c.name')
            ->addSelect('c.date')
            ->addSelect('c.id')
            ->addSelect('v.name venue')
            ->addSelect('v.city venue_city')
            ->addSelect('v.country venue_country')
            ->innerJoin('b.concert', 'c')
            ->innerJoin('c.venue', 'v')
            ->where('b.band = :band')
            ->setParameter(':band', $bandId);

        return $qb->getQuery()->getResult();
    }
}

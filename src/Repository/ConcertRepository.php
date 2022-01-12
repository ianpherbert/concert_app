<?php

namespace App\Repository;

use App\Entity\Concert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Concert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concert[]    findAll()
 * @method Concert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concert::class);
    }

    // @var $id is the id of the venue to be found in the database
    // @var $limit is the LIMIT of the query
    // Pass 0 to return all results
    public function getNextConcertsByVenue(int $id, int $limit = 0, int $exclude = 0): array
    {

        $date = date('Y-m-d H:i:s');

        $qb = $this->createQueryBuilder("c");
        $qb->select('c.name')
            ->addselect('c.name')
            ->addSelect('c.date')
            ->addSelect('c.id')
            ->where("c.id != :exclude")
            ->andWhere("c.venue = :id")
            ->andWhere("c.date >= :date")
            ->setParameters(['id' => $id, 'exclude' => $exclude, 'date' => $date])
            ->orderBy('c.date');

        // set limit on query if a limit is passed, if none, zero or a negative int is passed, it will return all
        if ($limit >  0) {
            $qb->setMaxResults($limit);
        }


        return $qb->getQuery()->getResult();
    }

    public function getConcertsByMonth(int $month, int $year)
    {
        $qb = $this->createQueryBuilder("c");
        // Create dateTime string for start and end of month.
        // Y m d H:i:s
        $start = $year . "-" . $month . "-" . 1 . " 00:00:00";
        $end = $year . "-" . $month . "-" . 31 . " 23:59:59";



        $qb->select('c.name')
            ->addSelect('c.id')
            ->addSelect('c.date')
            ->addSelect('v.name venue')
            ->addSelect('v.id venue_id')
            ->innerJoin('c.venue', 'v')
            ->andWhere('c.date >= :date_start')
            ->andWhere('c.date <= :date_end')
            ->setParameter('date_start', $start)
            ->setParameter('date_end', $end)
            ->orderBy('c.date');

        return $qb->getQuery()->getResult();
    }

    public function getConcert(int $id)
    {
        $qb = $this->createQueryBuilder("c");

        $qb->select('c.name')
            ->addSelect('c.date')
            ->addSelect('v.name venue_name')
            ->addSelect('v.id venue_id')
            ->addSelect('v.address venue_address')
            ->addSelect('v.city venue_city')
            ->addSelect('v.region venue_region')
            ->addSelect('v.country venue_country')
            ->addSelect('v.postal_code venue_postal_code')
            ->addSelect('v.photo venue_photo')
            ->addSelect('v.isPrivate')
            ->addSelect('v.capacity venue_capacity')
            ->innerJoin('c.venue', 'v')
            ->where('c.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getTodayConcerts(int $limit = 0)
    {
        // Create dateTime string for start and end of today.
        // Y m d H:i:s

        $today = date('Y-m-d');

        $start = $today . " 00:00:00";
        $end = $today . " 23:59:59";

        $qb = $this->createQueryBuilder("c");

        $qb->select('c.name')
            ->addSelect('c.id')
            ->addSelect('c.date')
            ->addSelect('v.name venue')
            ->addSelect('v.id venue_id')
            ->addSelect('v.photo venue_photo')
            ->innerJoin('c.venue', 'v')
            ->andWhere('c.date >= :date_start')
            ->andWhere('c.date <= :date_end')
            ->setParameter('date_start', $start)
            ->setParameter('date_end', $end)
            ->orderBy('c.date');

        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }


        return $qb->getQuery()->getResult();
    }
}

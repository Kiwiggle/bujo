<?php

namespace App\Repository;

use App\Entity\Mood;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mood|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mood|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mood[]    findAll()
 * @method Mood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mood::class);
    }

    public function findAllOrderedByDate() {
        return $this->createQueryBuilder('mood')
            ->orderBy('mood.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByDate($date, $user) {
        return $this->createQueryBuilder('mood')
            ->andWhere("mood.date = :date")
            ->andWhere("mood.user = :user")
            ->setParameter('date', $date)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonth($month, $user) {

        $start = new DateTime('2020-' . $month . '-01');
        $end = (clone $start)->modify('last day of this month');

        return $this->createQueryBuilder('mood')
            ->andWhere("mood.date BETWEEN :start AND :end")
            ->andWhere("mood.user = :user")
            ->orderBy("mood.date", 'ASC')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('user', $user)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    // /**
    //  * @return Mood[] Returns an array of Mood objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mood
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

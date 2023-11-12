<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Measurement>
 *
 * @method Measurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Measurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Measurement[]    findAll()
 * @method Measurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('m');

        $qb->where('m.location = :location')
            ->setParameter('location', $location)
            ->andWhere('m.date < :now')
            ->setParameter('now', date('Y-m-d'));

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }

    /** @return Measurement[] */
    public function findByCountryCodeAndCity(string $countryCode, string $city): array
    {
        return $this->createQueryBuilder('m',)
            ->join('m.location','l')
            ->where('l.country = :country')
            ->setParameter('country', $countryCode)
            ->andWhere('l.city = :city')
            ->setParameter('city', $city)
            ->getQuery()->getResult();
    }

//    /**
//     * @return Measurement[] Returns an array of Measurement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Measurement
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

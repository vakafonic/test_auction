<?php

namespace TestTask\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TestTask\Entity\AuctionPosition;

/**
 * @extends ServiceEntityRepository<AuctionPosition>
 *
 * @method AuctionPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuctionPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuctionPosition[]    findAll()
 * @method AuctionPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuctionPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuctionPosition::class);
    }

    public function save(AuctionPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AuctionPosition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AuctionPosition[] Returns an array of AuctionPosition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AuctionPosition
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

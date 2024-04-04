<?php

namespace App\Repository;

use App\Entity\Hotels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hotels|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotels|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotels[]    findAll()
 * @method Hotels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotels::class);
    }

    

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Hotels $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Hotels $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT h
                FROM App:Hotels h
                WHERE h.adresse LIKE :str or h.nom LIKE :str or h.nbetoiles LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }

    // /**
    //  * @return Hotels[] Returns an array of Hotels objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hotels
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

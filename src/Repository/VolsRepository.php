<?php

namespace App\Repository;


use App\Entity\Vols;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;




/**
 * @method Vols|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vols|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vols[]    findAll()
 * @method Vols[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class VolsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Vols::class);
    }
    function tri_asc()
    {
        return $this->createQueryBuilder('vols')
            ->orderBy('vols.date_depart ','ASC')
            ->getQuery()->getResult();
    }
    function tri_desc()
    {
        return $this->createQueryBuilder('vols')
            ->orderBy('vols.date_depart ','DESC')
            ->getQuery()->getResult();
    }

    public function search($criteria)
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.destination_aller','Vols')
            ->Where('v.destination_aller =: destination_aller')
            ->SetParameter('destination_aller',$criteria['destination_aller']->getDestinationaller())
            ->getQuery()
            ->getResult() ;


      /*  return $this->createQueryBuilder('v')
            ->orWhere('v.name :name')
            ->setParameter('name',$criteria['destination_aller']->getDestinationaller())
            ->getQuery()
        ->getResult() ;
        dd($array) ;} */


    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Vols $entity, bool $flush = true): void
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
    public function remove(Vols $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Vols[] Returns an array of Vols objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    }



    */

    /*
    public function findOneBySomeField($value): ?Vols
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')

            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

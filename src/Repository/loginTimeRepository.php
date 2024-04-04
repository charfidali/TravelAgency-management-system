<?php

namespace App\Repository;

use App\Entity\loginTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\Query\ResultSetMapping;





/**
 * @method loginTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method loginTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method loginTime[]    findAll()
 * @method loginTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class loginTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, loginTime::class);
    }


    public function selectbytoken($str)
    {
        
        return $this->getEntityManager()
        ->createQuery(
            'SELECT MAX(p.id) FROM APP\Entity\loginTime p 
            WHERE p.user.id= :str '
        )->setParameter('str', $str)
        ->getResult();
 
    }

    public function loginend($str)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 
            "UPDATE login_time SET lastlogin=CURRENT_TIMESTAMP where tokenverif=:str " ;
                $stmt = $conn->prepare($sql);
                $resultSet = $stmt->executeQuery(['str' => $str]);
       
    }
    public function countingtotalvisits()
    {

        $sql = 
            "SELECT COUNT(*) as 'counting',DATE_FORMAT(firstlogin,'%d-%b-%Y')as 'temp' FROM `login_time` where MONTH(firstlogin)=MONTH(NOW()) GROUP BY DATE_FORMAT(firstlogin,'%d %b %Y') " ;
            $rsm = new ResultSetMapping;
            $rsm->addScalarResult('counting', 'counting');
            $rsm->addScalarResult('temp', 'temp');
            $query = $this->_em->createNativeQuery($sql, $rsm);
            return $query->getResult();
       
    }
    public function countingtotalvisitsbyuser()
    {

        $sql = 
            "SELECT COUNT(DISTINCT user_id) as 'counting',DATE_FORMAT(firstlogin,'%d-%b-%Y')as 'temp' FROM `login_time` where MONTH(firstlogin)=MONTH(NOW())  GROUP BY DATE_FORMAT(firstlogin,'%d %b %Y') " ;
            $rsm = new ResultSetMapping;
            $rsm->addScalarResult('counting', 'counting');
            $rsm->addScalarResult('temp', 'temp');
            $query = $this->_em->createNativeQuery($sql, $rsm);
            return $query->getResult();
       
    }

}
<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    // /**
    //  * @return Formation[] Returns an array of Formation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getFirstFormation(): array
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT * FROM formation f WHERE f.multiimg !='' ";
        $stmt=$em->prepare($req);
        $stmt->execute();
       
        $data = $stmt->fetchAllAssociative();
        return $data;
    }
    public function getRestFormation(): array
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT * FROM formation f WHERE f.multiimg IS NULL ORDER BY f.id DESC ";
        $stmt=$em->prepare($req);
        $stmt->execute();
       
        $data = $stmt->fetchAllAssociative();
        return $data;
    }

    
}

<?php

namespace App\Repository;

use App\Entity\Asksubscibe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Asksubscibe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asksubscibe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asksubscibe[]    findAll()
 * @method Asksubscibe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsksubscibeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asksubscibe::class);
    }

    // /**
    //  * @return Asksubscibe[] Returns an array of Asksubscibe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Asksubscibe
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function getListAsker()
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT e.*, u.forename, u.name, u.img, u.telnumber FROM `asksubscibe` e INNER JOIN user u ON u.id=e.recruteur_id WHERE e.status='En attente' ORDER BY id DESC";
        $stmt=$em->prepare($req);
        $stmt->execute();
       
        $data = $stmt->fetchAllAssociative();
        return $data;
    }
}

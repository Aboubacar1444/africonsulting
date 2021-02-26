<?php

namespace App\Repository;

use App\Entity\Ads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ads|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ads|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ads[]    findAll()
 * @method Ads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ads::class);
    }

    public function getPub(): array
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT * FROM `ads` WHERE type ='Publicité' ORDER BY id DESC LIMIT 8";
        $stmt=$em->prepare($req);
        $stmt->execute();
       
        $data = $stmt->fetchAllAssociative();
        return $data;
    }

    public function getPart(): array
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT * FROM `ads` WHERE type ='Partenariat' ORDER BY id DESC";
        $stmt=$em->prepare($req);
        $stmt->execute();
       
        $data = $stmt->fetchAllAssociative();
        return $data;
    }
}

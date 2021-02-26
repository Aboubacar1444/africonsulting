<?php

namespace App\Repository;

use App\Entity\Candidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Candidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidature[]    findAll()
 * @method Candidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }

    public function getApply($istreated)
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT c.*, e.title, u.img, u.forename, u.name FROM `candidature` c INNER JOIN emploi e ON e.id = c.emploi_id INNER JOIN user u ON u.id=c.candidat_id WHERE c.istreated = ? ORDER BY c.id ASC ";
        $stmt=$em->prepare($req);
        $stmt->execute(array($istreated));
       
        $data = $stmt->fetchAllAssociative();
        return $data;
    }
}

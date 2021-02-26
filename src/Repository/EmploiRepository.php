<?php

namespace App\Repository;

use App\Entity\Emploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emploi[]    findAll()
 * @method Emploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emploi::class);
    }

    public function getCounted(): array
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT *, COUNT(*) p FROM `emploi`";
        $stmt=$em->prepare($req);
        $stmt->execute();
       
        $data = $stmt->fetchAllAssociative();
        return $data;
    }

    public function getEmploi($type)
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "SELECT *  FROM `emploi` p WHERE p.type ='".$type."'";
        $stmt=$em->prepare($req);
        // $stmt->execute(array($type));
       
       
        return $stmt;
    }

    public function DeletebyRecruteur($id)
    {
        $em = $this->getEntityManager()->getConnection();

        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= "DELETE FROM `emploi` p WHERE p.recruteur_id = ? ";
        $stmt=$em->prepare($req);
        $data=$stmt->execute(array($id));
       
       return $data;
    }

    public function SearchByTitle(string $title, string $field): array
    {
        $em = $this->getEntityManager();
        //SELECT * FROM tb_acedemie WHERE localiteID j'ai juste renomme la table 
        $req= $em->createQuery(
                "SELECT DISTINCT e FROM App\Entity\Emploi e
                 WHERE e.title LIKE :title
                 OR e.title LIKE :field   
                 ORDER BY e.id DESC"
                
            )->setParameter('title', $title)
             ->setParameter('field', '%'.$field.'%');       
       return $req->getResult();
    }

    public function SearchBySociety(string $society, string $field): array
    {
        $em = $this->getEntityManager();
        $req= $em->createQuery(
                "SELECT DISTINCT e FROM App\Entity\Emploi e
                 WHERE e.societyname LIKE :society
                 OR e.societyname LIKE :field   
                 ORDER BY e.id DESC"
                
            )->setParameter('society', $society)
             ->setParameter('field', '%'.$field.'%');       
       return $req->getResult();
    }
  
    
    
}

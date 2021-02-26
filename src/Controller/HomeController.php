<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Repository\AdsRepository;
use App\Repository\CandidatureRepository;
use App\Repository\CategorieRepository;
use App\Repository\ContratRepository;
use App\Repository\EmploiRepository;
use App\Repository\LocaliteRepository;
use App\Repository\PaysRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, CandidatureRepository $candidatureRepository, AdsRepository $adsRepository, AbonnementRepository $abonnementRepository, UserRepository $userRepository, EmploiRepository $emploiRepository, PaysRepository $paysRepository, CategorieRepository $categorieRepository, LocaliteRepository $localiteRepository, ContratRepository $contratRepository): Response
    {
        
        $em= $this->getDoctrine()->getManager();
        $emploi = $emploiRepository->findBy(
            ['type'=>'Offre d\'emploi'],
            ['id'=>'DESC'],
            3,
        );
        $expireAbonnement=$abonnementRepository->findAll();
        foreach ($expireAbonnement as $v) {
            if(date('d/m/Y H:i:s') >= date($v->getDatefin())){
                $v->setIsactive('No');
                $em->flush(); 
            }              
        }
        
        
        $user = $userRepository->findAll();
        
        $pays= $paysRepository->findAll();
        $categories=$categorieRepository->findAll();
        $ville= $localiteRepository->findAll();
        $contrat= $contratRepository->findAll();
        
        if($this->getUser()){
            $role=['ROLE_RECRUTEUR', 'ROLE_USER'];
            // dd($role);
            if($this->getUser()->getRoles() == $role){
                $recruteur=$this->getUser()->getId();
                $abonnement = $abonnement=$abonnementRepository->findBy(
                    ['user'=>$recruteur],
                    ['id'=>'DESC'],
                    1,
                );
                $emploiR=$emploiRepository->findBy(
                    ['recruteur'=>$recruteur],
                    ['id'=>'ASC'],
                );

                foreach ($abonnement as $k) { 
                    if(date('d/m/Y H:i:s') >= date($k->getDatefin())){
                        $k->setIsactive('No');
                        $em->flush();               
                        
                        foreach ($emploiR as $v) {
                            // var_dump($v->getImg());
                            // die;
                            $file=$v->getImg();
                            $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/files/emploi/'.$file;
                            $filesystem = new Filesystem();
                            $filesystem->remove($publicDirectory);
                            $em->remove($v);
                            // $img=$v->
                            $em->flush();
                        }
        
                      $this->addFlash('error', 'Votre abonnement '. $k->getType(). ' est fini. Pour continuer à exploiter vos avantages ou encore plus, abonnez-vous de noveau!');
                    }
                }           

            }else{
                $abonnement=[];
            }
        }else{
            $abonnement=[];
        }

        $expire=$emploiRepository->findAll();
        foreach ($expire as $e) {

            if (date('d/m/Y H:i:s') >= date($e->getDatelimit())) {
                $applies=$candidatureRepository->findBy(
                    ['emploi'=>$e->getId(),]
                );
                if($applies){
                   foreach($applies as $a){
                       $em->remove($a);
                       $em->flush();
                   }   
                }
                $filee=$e->getImg();
                $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/files/emploi/'.$filee;
                $filesystem = new Filesystem();
                $filesystem->remove($publicDirectory);
                $em->remove($e);
                $em->flush();
                return $this->redirectToRoute('index');
            }
        }
        $expireads=$adsRepository->findBy(
            ['type'=>'Publicité'],
        );
        foreach ($expireads as $ads) {
            if(date('d/m/Y H:i:s') >= date($ads->getDatefin())){
                $fileads=$ads->getImg();
                $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/files/emploi/'.$fileads;
                $filesystem = new Filesystem();
                $filesystem->remove($publicDirectory);
                $em->remove($ads);
                $em->flush();
            }
        }
        
        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
       
        
        return $this->render('index.html.twig', [
            'homeActive'=>1,
            'emploi'=>$emploi,
            'pays'=>$pays,
            'categories'=>$categories,
            'ville'=>$ville,
            'contrat'=>$contrat,
            'user'=>$user,
            'type'=>'offres',
            'abonnement'=>$abonnement,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ] );
    }

    /**
    * @Route("/getLocalite/{id}", name="getLocalite")
    */
    public function getLocalite(LocaliteRepository $a, $id)
    {
        $result = $a->getLocalite($id);        

        return new JsonResponse ($result);   
        
    }

}

<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Localite;
use App\Entity\User;
use App\Repository\AbonnementRepository;
use App\Repository\AdsRepository;
use App\Repository\CandidatureRepository;
use App\Repository\CategorieRepository;
use App\Repository\ContratRepository;
use App\Repository\DomaineRepository;
use App\Repository\EmploiRepository;
use App\Repository\LocaliteRepository;
use App\Repository\PaysRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/emploi")
 */
class EmploiController extends AbstractController
{
    /**
     * @Route("/{type}", name="emploi")
     */
    public function index(Request $request, CandidatureRepository $candidatureRepository, AdsRepository $adsRepository, AbonnementRepository $abonnementRepository,EntityManagerInterface $em, PaginatorInterface $paginator,  UserRepository $userRepository, PaysRepository $paysRepository, CategorieRepository $categorieRepository, LocaliteRepository $localiteRepository, ContratRepository $contratRepository, EmploiRepository $emploiRepository): Response
    {
        

        $type= $request->get('type');
        if ($type=='offres') {
            $typeActive = 1;
            $list="Offre d'emploi";
            $emploi= $emploiRepository->findBy(
                ['type'=>$list],
                ['id'=>'DESC'],
            );
            $g=false;
            $name=false;
            
        }
        elseif ($type=='appels'){
            $typeActive = 2;
            $list="Appel d'offre";
            $emploi= $emploiRepository->findBy(
                ['type'=>$list],
                ['id'=>'DESC'],
            );
            $g=false;
            $name=false;
        }

        if($request->get('pays')){
            $pays= $request->get('pays');
            $g='pays';
            $list=false;
            $emploi= $emploiRepository->findBy(
                ['pays'=>$pays],
                ['id'=>'DESC'],
            );
        }

        if($request->get('localite')){
            $localite= $request->get('localite');

            $g='localite';

           
            $list=false;
            $emploi= $emploiRepository->findBy(
                ['localite'=>$localite],
                ['id'=>'DESC'],
            );
        }

        if($request->get('contrat')){
            $contrat= $request->get('contrat');
           
            $g='contrat';

           
            $list=false;
            $emploi= $emploiRepository->findBy(
                ['contrat'=>$contrat],
                ['id'=>'DESC'],
            );
        }

        if($request->get('categorie')){
            $categorie= $request->get('categorie');
           
            $g='categorie';
            
          
            $list=false;
            $emploi= $emploiRepository->findBy(
                ['categorie'=>$categorie],
                ['id'=>'DESC'],
            );
        }
        

        if($request->get('name')){
            $name=$request->get('name');
        }
        $pagination = $paginator->paginate(
            $emploi,
            $request->query->getInt('page', 1),
            3
            
        );
        
        $pays= $paysRepository->findAll();
        $categories=$categorieRepository->findAll();
        $ville= $localiteRepository->findAll();
        $contrat= $contratRepository->findAll();
        $user = $userRepository->findAll();

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
            }else{
                $abonnement=[];
            }
        }
        else{
            $abonnement=[];
        }

        $em=$this->getDoctrine()->getManager();

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
                
                $em->remove($e);
                
                $file=$e->getImg();
                $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/files/emploi/'.$file;
                $filesystem = new Filesystem();
                $filesystem->remove($publicDirectory);
                $em->flush();

                return $this->redirectToRoute('emploi');
            }
        }

        $counted=$emploiRepository->getCounted();
        
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            

        return $this->render("emploi/index.html.twig",[
            'homeActive'=>2,
            'typeActive'=>$typeActive,
            'emploi'=>$pagination,
            'pays'=>$pays,
            'categories'=>$categories,
            'ville'=>$ville,
            'contrat'=>$contrat,
            'user'=>$user,
            'type'=>$type,
            'g'=>$g,
            'name'=>$name,
            'abonnement'=>$abonnement,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);

    }

    /**
     * @Route("/candidature/{emploi}/{candidate}", name="candidature")
     */
    public function Candidature(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, UserRepository $userRepository, FileUploader $fileUploader): Response
    {
        $type= $request->get('emploi');
        $candidat=$request->get('candidate');
        if($type){
            $emploi=$emploiRepository->findOneBy(['id'=>$type]);
        }
        if($candidat){
            $findedUser=$userRepository->findOneBy(['id'=>$candidat]);
            
        }
        
        $em=$this->getDoctrine()->getManager(); 
            if($request->isMethod('POST')){
                $lettrem=$request->files->get('lm');
                
                if($lettrem){
                    $lm=$fileUploader->upload($lettrem);
                    $findedUser->setLettrem($lm);
                }
                $otherdoc=$request->files->get('other');
                if($otherdoc){
                    $other=$fileUploader->upload($otherdoc);
                    $findedUser->setOther($other);
                }
               
                $em->flush();
                
                $candidature= new Candidature();
                $candidature->setEmploi($emploi);
                $candidature->setCandidat($findedUser);
                $candidature->setIstreated('No');

                $em->persist($candidature);
                $em->flush();
                

                $this->addFlash("success", "Votre candidature a bien été receptionnée!");
                return $this->redirectToRoute('index');

            }
        
            $ads=$adsRepository->getPub();
            $part=$adsRepository->getPart();
    

        $counted=$emploiRepository->getCounted();
        return $this->render("emploi/candidature.html.twig",[
            'homeActive'=>2,
            'typeActive'=>false,
            'emploi'=>$emploi,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
        
        
        
    }

    /**
     * @Route("/details/{emploi}", name="emploidetails")
     */
    public function emploiDetails(Request $request, AdsRepository $adsRepository, AbonnementRepository $abonnementRepository, EmploiRepository $emploiRepository): Response
    {
        $type= $request->get('emploi');
        if($type){
            $emploi=$emploiRepository->findOneBy(['id'=>$type]);
        }
        
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
            }
            else{
                $abonnement=[];
            }
        }
        else{
            $abonnement=[];
        }

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        $counted=$emploiRepository->getCounted();
        return $this->render("emploi/emploidetails.html.twig",[
            'homeActive'=>2,
            'typeActive'=>false,
            'emploi'=>$emploi,
            'abonnement'=>$abonnement,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    /**
     * @isGranted("ROLE_USER")
     * @Route("/updatepassworduser/{id}", name="updatepassuser")
     */
    public function updatePassword(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, AbonnementRepository $abonnementRepository, UserRepository $userRepository, UserPasswordEncoderInterface $encoder): Response
    {
       $userr= new User();
     
        $em=$this->getDoctrine()->getManager();
        if($request->get('id')){
            $id=$request->get('id');
            $userProfile=$userRepository->findOneBy(['id'=>$id]);
            if(!$userProfile){
                $this->redirectToRoute('index');
            }
           
        }
        if($request->isMethod('POST')){
            
            
            $newpass=$request->request->get('newpass');
            $encodedpass=$encoder->encodePassword($userr, $newpass);
            $userProfile->setPassword($encodedpass);
            $em->flush();
            // dd($userProfile->getPassword(), $userProfile->getAgent()->getPassword());
            $this->addFlash("success", "Modification de votre mot de passe complétée avec succès.");
            return $this->redirectToRoute('index'); 
            
        }

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
            }else{
                $abonnement=[];
            }
        }

        $counted=$emploiRepository->getCounted();

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            

        return $this->render('dashboards/user/updatepassword.html.twig',[
            'homeActive'=>'profile',
            'typeActive'=>'updatepass',
            'userprofile'=>$userProfile,
            'abonnement'=>$abonnement,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    /**
     * @isGranted("ROLE_USER")
     * @Route("/updateuser/{id}", name="updateuser")
     */
    public function updateUser(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, AbonnementRepository $abonnementRepository, UserRepository $userRepository, LocaliteRepository $localiteRepository, DomaineRepository $domaineRepository, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader): Response
    {
       $user= new User();
     
        $em=$this->getDoctrine()->getManager();
        if($request->get('id')){
            $id=$request->get('id');
            $userProfile=$userRepository->findOneBy(['id'=>$id]);
            if(!$userProfile){
                $this->redirectToRoute('index');
            }
           
        }
        if($request->isMethod('POST')){
            
            
            $updateuser=$request->request->all();
            if($request->files->get('cv')){
                $cv=$request->files->get('cv');
                $cvu=$fileUploader->upload($cv);
                $userProfile->setCv($cvu);
            }
            if($request->files->get('img')){
                $img=$request->files->get('img');
                $imgu=$fileUploader->upload($img);
                $userProfile->setImg($imgu);
            }
            $newd=$domaineRepository->findOneBy(['id'=>$updateuser['domaine']]);
            if($newd){
                $userProfile->setDomaine($newd);
            }
            $newl=$localiteRepository->findOneBy(['id'=>$updateuser['localite']]);
            if($newl){
                $userProfile->setLocalite($newl);
            }
            $userProfile->setSexe($updateuser['sexe']);
            $userProfile->setName($updateuser['name']);
            $userProfile->setForename($updateuser['forename']);
            $userProfile->setSocietyname($updateuser['societe']);
            $userProfile->setTelnumber($updateuser['telnumber']);
            $em->flush();
            // dd($userProfile->getPassword(), $userProfile->getAgent()->getPassword());
            $this->addFlash("success", "Modification de votre profil complétée avec succès.");
            return $this->redirectToRoute('index'); 
            
        }

        $localite= $localiteRepository->findAll();
        $domaine= $domaineRepository->findAll();

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
            }else{
                $abonnement=[];
            }
        }

        $counted=$emploiRepository->getCounted();

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        return $this->render('dashboards/user/index.html.twig',[
            'homeActive'=>'profile',
            'typeActive'=>'updateuser',
            'profile'=>$userProfile,
            'localite'=>$localite,
            'domaine'=>$domaine,
            'abonnement'=>$abonnement,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }
    
    /**
     * @isGranted("ROLE_CANDIDAT")
     * @Route("/applies/{id}", name="applies")
     */
    public function Applies(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, CandidatureRepository $candidatureRepository, UserRepository $userRepository): Response
    {
        $id=$request->get('id');
        // $user=$userRepository->findOneBy(['id'=>$id]);
        $applies=$candidatureRepository->findBy(
            ['candidat'=>$id, 'istreated'=>'Yes'],
            ['emploi'=>'DESC'],
        );
        
        $counted=$emploiRepository->getCounted();

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        return $this->render('dashboards/user/candidat/index.html.twig',[
            'homeActive'=>'applies',
            'typeActive'=>false,
            'applies'=>$applies,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    

}

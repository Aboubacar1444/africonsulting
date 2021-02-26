<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Ads;
use App\Entity\Categorie;
use App\Entity\Domaine;
use App\Entity\Emploi;
use App\Entity\Localite;
use App\Entity\Pays;
use App\Entity\User;
use App\Form\AbonnementType;
use App\Form\AdsType;
use App\Form\CategorieType;
use App\Form\DomaineType;
use App\Form\EmploiType;
use App\Form\LocalityType;
use App\Form\PaysType;
use App\Repository\AbonnementRepository;
use App\Repository\AdsRepository;
use App\Repository\AsksubscibeRepository;
use App\Repository\CandidatureRepository;
use App\Repository\EmploiRepository;
use App\Repository\UserRepository;
use App\Service\EmploiFilesUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_MODERATOR")
 * @Route("/moderator")
 */
class ModeratorController extends AbstractController
{
    /**
     * @Route("/", name="moderator")
     */
    public function index(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, EmploiFilesUploader $emploiFilesUploader): Response
    {
        $emploi= new Emploi();
        $formemploi=$this->createForm(EmploiType::class, $emploi);
        $formemploi->handleRequest($request);
        if($formemploi->isSubmitted() && $formemploi->isValid()){
            $emploi=$formemploi->getData();
            $img=$formemploi['img']->getData();
            $datepost=date($formemploi['datepost']->getData());
            $datelimit=date($formemploi['datelimit']->getData());
            $emploi->setDatepost($datepost);
            $emploi->setDatelimit($datelimit);
            if($img){
                $imgu=$emploiFilesUploader->upload($img);
                $emploi->setImg($imgu);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();
            $this->addFlash("success", "Emploi ajouté avec succès!");
            return $this->redirectToRoute('moderator');
        }
        
        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
        return $this->render('dashboards/mod/index.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'newemploi',            
            'form'=>$formemploi->createView(),
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }
    /**
     * @Route("/newpays", name="newpays")
     */
    public function newPays(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository): Response
    {
        $pays= new Pays();
        $form=$this->createForm(PaysType::class, $pays);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pays=$form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($pays);
            $em->flush();
            $this->addFlash("success", "Pays ajouté avec succès!");
            return $this->redirectToRoute('newpays');
        }
        

        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();

        return $this->render('dashboards/mod/pays.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'newpays',            
            'form'=>$form->createView(),
            'counted'=>$counted,
            'ads' => $ads,
            'part'=>$part,
        ]);
    }
    /**
     * @Route("/newlocality", name="newlocality")
     */
    public function newLocality(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository): Response
    {
        $localite= new Localite();
        $form=$this->createForm(LocalityType::class, $localite);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $localite=$form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($localite);
            $em->flush();
            $this->addFlash("success", "Localité ajoutée avec succès!");
            return $this->redirectToRoute('newlocality');
        }
        

        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
        return $this->render('dashboards/mod/localite.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'newlocality',            
            'form'=>$form->createView(),
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    /**
     * @Route("/newcategorie", name="newcategorie")
     */
    public function newCategorie(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository): Response
    {
        $categorie= new Categorie();
        $form=$this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $categorie=$form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            $this->addFlash("success", "Catégorie ajoutée avec succès!");
            return $this->redirectToRoute('newcategorie');
        }
        

        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
        return $this->render('dashboards/mod/categorie.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'newcategorie',            
            'form'=>$form->createView(),
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }
    /**
     * @Route("/newdomaine", name="newdomaine")
     */
    public function newDomaine(Request $request, AdsRepository $adsRepository,  EmploiRepository $emploiRepository): Response
    {
        $domaine= new Domaine();
        $form=$this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $domaine=$form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($domaine);
            $em->flush();
            $this->addFlash("success", "Domaine ajouté avec succès!");
            return $this->redirectToRoute('newdomaine');
        }
        

        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
        return $this->render('dashboards/mod/domaine.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'newdomaine',            
            'form'=>$form->createView(),
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }
    /**
     * @Route("/listuser", name="listuser")
     */
    public function ListUser(Request $request): Response
    {
       
        // $counted=$emploiRepository->getCounted();
        return $this->render('dashboards/mod/listuser.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'listuser',            
        ]);
    }

    /**
     * @Route("/infos/{id}", name="infos")
     */
    public function infosUser(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository,UserRepository $userRepository): Response
    {
        $id=$request->get('id');
        $info=$userRepository->findOneBy(['id'=>$id]);
        

        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
        return $this->render('dashboards/mod/infosuser.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'listuser',
            'info'=>$info,    
            'counted'=>$counted,  
            'ads'=>$ads,
            'part'=>$part,      
        ]);
    }
    
    /**
     * @Route("/updatepassword/{id}", name="updatepass")
     */
    public function updatePassword(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository,UserRepository $userRepository, UserPasswordEncoderInterface $encoder): Response
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
        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();

        return $this->render('Dashboards/mod/updatepassword.html.twig',[
            'homeActive'=>'profile',
            'typeActive'=>'updatepass',
            'userprofile'=>$userProfile,
            'counted'=>$counted,
            'part'=>$part,
            'ads'=>$ads,
        ]);
    }
    

    /**
     * @Route("/findapplies", name="findapplies")
     */
    public function findApplies(EmploiRepository $emploiRepository, AdsRepository $adsRepository): Response
    {
     
        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
        return $this->render('dashboards/mod/applies.html.twig',[
            'homeActive'=>'candidature',
            'typeActive'=>false,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,

        ]);
    
    }

    /**
     * @Route("/treatapply/{id}", name="treatapply")
     */
    public function treatApply(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository ,CandidatureRepository $candidatureRepository): Response
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->get('id');
        $candidat=$candidatureRepository->findOneBy(['id'=>$id]);

        if($request->get('decision')){
            $candidat->setIstreated('Yes');
            $candidat->setDecision($request->get('decision'));
            $em->flush();
            $this->addFlash("success", "Traitement de la demande effectuée avec succès avec succès.");
            return $this->redirectToRoute('findapplies');

        }

        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();

        return $this->render('dashboards/mod/treatapply.html.twig',[
            'homeActive'=>'candidature',
            'typeActive'=>false,
            'candidat'=>$candidat,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    
    }



    /**
     * @Route("/listcandidat", name="listcandidat")
     */
    public function ListCandidat(Request $request, UserRepository $userRepository): Response
    {
        $type="Candidat";
        $Candidat= $userRepository->getList($type);
        return new JsonResponse($Candidat);
          
    }
    /**
     * @Route("/listrecruteur", name="listrecruteur")
     */
    public function ListRecruteur(Request $request, UserRepository $userRepository): Response
    {
        $type="Recruteur";
        $Recruteur= $userRepository->getList($type);
        return new JsonResponse($Recruteur);
          
    }

    

    /**
    * @Route("/findapply", name="findapply")
    */
    public function getApply(Request $request, CandidatureRepository $a)
    {
        
        $result = $a->getApply('No');        
        return new JsonResponse ($result);   
        
    }


    /**
     * @Route("/listsubscriber", name="listsubscriber")
     */
    public function listSubscriber(Request $request, AbonnementRepository $aboRepository): Response
    {
        
        $List= $aboRepository->getListSubscriber();
        return new JsonResponse($List);
          
    }

    /**
     * @Route("/listasker", name="listasker")
     */
    public function listAsker(Request $request, AsksubscibeRepository $askRepository): Response
    {
        $List= $askRepository->getListAsker();
        return new JsonResponse($List);
          
    }


    /**
     * @Route("/newabonnement", name="newabonnement")
     */
    public function newAbonnement(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, AsksubscibeRepository $askRepository): Response
    {
        $abonnement= new Abonnement();
        $form=$this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $abonnement=$form->getData();
            $datedeb= $abonnement->getDatedeb();
            $datefin= $abonnement->getDatefin();
            
            $datedeb= date($datedeb.' H:i:s ');
            $datefin= date($datefin.' H:i:s');
            // dd($datedeb,$datefin);
            
            $abonnement->setDatedeb($datedeb);
            $abonnement->setDatefin($datefin);

            // dd($abonnement);
            $em = $this->getDoctrine()->getManager();
            $em->persist($abonnement);
            $em->flush();
            
            $newabo=$askRepository->findOneBy(['recruteur'=>$abonnement->getUser(),'status'=>'En attente']);
            $newabo->setStatus('Abonné');
            $em->flush();
            $this->addFlash("success", "Abonnement de ".$abonnement->getUser()->getForename()." ".$abonnement->getUser()->getName()." attribué avec succès!");
            return $this->redirectToRoute('newabonnement');
        }
        
        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();

        return $this->render('dashboards/mod/newabonnement.html.twig',[
            'homeActive'=>'newabonnement',
            'typeActive'=>'newabonnement',            
            'form'=>$form->createView(),
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    /**
     * @Route("/subscription/management", name="gestionabo")
     */
    public function gestionAbonnement(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository): Response
    {
        $abonnement= new Abonnement();
        $form=$this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $abonnement=$form->getData();
            $datedeb= $abonnement->getDatedeb();
            $datefin= $abonnement->getDatefin();
            
            $datedeb= date($datedeb.' H:i:s ');
            $datefin= date($datefin.' H:i:s');
            // dd($datedeb,$datefin);
            
            $abonnement->setDatedeb($datedeb);
            $abonnement->setDatefin($datefin);

            // dd($abonnement);
            $em = $this->getDoctrine()->getManager();
            $em->persist($abonnement);
            $em->flush();
            $this->addFlash("success", "Abonnement de ".$abonnement->getUser()->getForename()." ".$abonnement->getUser()->getName()." attribué avec succès!");
            return $this->redirectToRoute('newabonnement');
        }
        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();

        return $this->render('dashboards/mod/gestionabo.html.twig',[
            'homeActive'=>'gestionabo',
            'typeActive'=>'gestionabo',            
            'form'=>$form->createView(),
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    /**
     * @Route("/ads", name="ads")
     */
    public function Ads(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, EmploiFilesUploader $emploiFilesUploader): Response
    {
        $abonnement= new Ads();
        $form=$this->createForm(AdsType::class, $abonnement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $abonnement=$form->getData();
            $img=$form['img']->getData();

            if($img){
                $imgu=$emploiFilesUploader->upload($img);
                $abonnement->setImg($imgu);
            }
            $datefin= $abonnement->getDatefin();
            if($datefin){
                $datefin= date($datefin.' H:i:s');
                $abonnement->setDatefin($datefin);
            }   

            // dd($abonnement);
            $em = $this->getDoctrine()->getManager();
            $em->persist($abonnement);
            $em->flush();
            $this->addFlash("success", "Ads Ajouté avec succès!");
            return $this->redirectToRoute('ads');
        }
        $counted=$emploiRepository->getCounted();
        
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
           
        return $this->render('dashboards/mod/ads.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'ads',            
            'form'=>$form->createView(),
            'counted'=>$counted, 
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }


}

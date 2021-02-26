<?php

namespace App\Controller;

use App\Entity\Asksubscibe;
use App\Entity\Emploi;
use App\Form\AppelType;
use App\Repository\AbonnementRepository;
use App\Repository\AdsRepository;
use App\Repository\CandidatureRepository;
use App\Repository\EmploiRepository;
use App\Repository\UserRepository;
use App\Service\EmploiFilesUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;



/**
 * @Route("/gestion")
 */
class GestionController extends AbstractController
{
    /**
     * @Route("/", name="gestion")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('index');
    }
    /**
     * @isGranted("ROLE_RECRUTEUR")
     * @Route("/applyer", name="applyer")
     */
    public function applyer(Request $request, EmploiRepository $emploiRepository, AbonnementRepository $abonnementRepository, CandidatureRepository $candidatureRepository, UserRepository $userRepository): Response
    {
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
        }
        
        $counted=$emploiRepository->getCounted();

        return $this->render('dashboards/user/recruteur/index.html.twig',[
            'homeActive'=>'applyer',
            'typeActive'=>false, 
            'abonnement'=>$abonnement,  
            'counted'=>$counted,        
        ]);
    }

    /**
     * @isGranted("ROLE_RECRUTEUR")
     * @Route("/abonnement/{id}", name="abonnement")
     */
    public function abonnement(Request $request, EmploiRepository $emploiRepository, AbonnementRepository $abonnementRepository, CandidatureRepository $candidatureRepository, UserRepository $userRepository): Response
    {
        
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
        }


        $counted=$emploiRepository->getCounted();

        return $this->render('dashboards/user/recruteur/abonnement.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'abonnement', 
            'abonnement'=>$abonnement, 
            'counted'=>$counted,          
        ]);
    }

    /**
     * @isGranted("ROLE_RECRUTEUR")
     * @Route("/suscribe/{society}/{type}", name="suscribe")
     */
    public function suscribe(Request $request, EmploiRepository $emploiRepository, AbonnementRepository $abonnementRepository, CandidatureRepository $candidatureRepository, UserRepository $userRepository): Response
    {
        $asksub= new Asksubscibe();
        $em=$this->getDoctrine()->getManager();
        $type=$request->get('type');
        $society=$request->get('society');
        $recruteur= $userRepository->findOneBy(['societyname'=>$society]);

        if ($request->isMethod('POST')) {
            $a = $request->request->all();
            $cashtype=$a['cashtype'];
            $date=date('d/m/Y');

            $asksub->setData($date);
            $asksub->setAbonnement($type);
            $asksub->setRecruteur($recruteur);
            $asksub->setPaiment($a['cashtype']);
            $asksub->setStatus('En attente');
            $em->persist($asksub);
            $em->flush();
            
            return new JsonResponse($cashtype);
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
        }
        

        $counted=$emploiRepository->getCounted();
        return $this->render('dashboards/user/recruteur/suscribe.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'abonnement',
            'type'=>$type,
            'society'=>$society,
            'abonnement'=>$abonnement,
            'counted'=>$counted,            
        ]);
    }

    /**
     * @isGranted("ROLE_RECRUTEUR")
     * @Route("/appeloffre", name="appeloffre")
     */
    public function appelOffre(Request $request, EmploiRepository $emploiRepository, EmploiFilesUploader $emploiFilesUploader, AbonnementRepository $abonnementRepository, CandidatureRepository $candidatureRepository, UserRepository $userRepository): Response
    {
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
        }

        $emploi= new Emploi();
        $formemploi=$this->createForm(AppelType::class, $emploi);
        $formemploi->handleRequest($request);
        if($formemploi->isSubmitted() && $formemploi->isValid()){
            $emploi=$formemploi->getData();
            $img=$formemploi['img']->getData();
            $datepost=date($formemploi['datepost']->getData());
            $datelimit=date($formemploi['datelimit']->getData());

            $emploi->setDatepost($datepost);
            $emploi->setDatelimit($datelimit);
            $emploi->setRecruteur($this->getUser());
            
            if($img){
                $imgu=$emploiFilesUploader->upload($img);
                $emploi->setImg($imgu);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();
            $this->addFlash("success", "Emploi ajouté avec succès!");
            return $this->redirectToRoute('index');
        }
        
        $counted=$emploiRepository->getCounted();
        return $this->render('dashboards/user/recruteur/appeloffre.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'appeloffre',
            'abonnement'=>$abonnement,
            'form'=>$formemploi->createView(),  
            'counted'=>$counted,  
        ]);
    }

    
    
    
    
    
    
    /**
     * @Route("/listc", name="listc")
     */
    public function ListCandidat(Request $request, UserRepository $userRepository): Response
    {
        $type="Candidat";
        $Candidat= $userRepository->getList($type);
        return new JsonResponse($Candidat);
          
    }

    /**
     * @Route("/infoscandidat/{id}/{recruteur}", name="infoscandidat")
     */
    public function infosUser(Request $request, EmploiRepository $emploiRepository, UserRepository $userRepository, AbonnementRepository $abonnementRepository): Response
    {
        $id=$request->get('id');
        $recruteur=$request->get('recruteur');
        $info=$userRepository->findOneBy(['id'=>$id]);
        $abonnement=$abonnementRepository->findBy(
            ['user'=>$recruteur],
            ['id'=>'DESC'],
            1,
        );

        // foreach ($abonnement as $key => $value) {
        //    dd($value);
        // }
        // // dd($typeabo);
        
        $counted=$emploiRepository->getCounted();
        return $this->render('dashboards/user/recruteur/infosuser.html.twig',[
            'homeActive'=>'options',
            'typeActive'=>'listuser',
            'info'=>$info, 
            'abonnement'=>$abonnement, 
            'counted'=>$counted,          
        ]);
    }


    /**
     * @Route("/search/emploi", name="search")
     */
    public function search(Request $request,AdsRepository $adsRepository, PaginatorInterface $paginator, EmploiRepository $emploiRepository): Response
    {
        if ($request->isMethod('POST')) {
            
            $field=$request->request->get('field');
            $searchbytitle= $emploiRepository->SearchByTitle($field,$field); 
            $searchbysociety= $emploiRepository->SearchBySociety($field,$field);
            
            if($searchbytitle){
                $result = $searchbytitle;
               
            }elseif ($searchbysociety){
                $result=$searchbysociety;
            }else{
                
                $this->addFlash('error','Oups . . les données que vous recherchez n\'existent pas.');
                return $this->redirectToRoute('emploi',['type'=>'offres']);;
            }
                
            
        }
        $pagination = $paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            4
            
        );

        $abonnement = [];
        $counted=$emploiRepository->getCounted();

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        return $this->render('search.html.twig',[
            'homeActive'=>false,
            'typeActive'=>false,
            'searched'=>$pagination,  
            'counted'=>$counted,
            'abonnement'=>$abonnement, 
            'ads'=>$ads,
            'part'=>$part,  
        ]);
          
    }

}

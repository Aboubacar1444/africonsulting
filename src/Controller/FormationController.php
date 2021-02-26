<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\AdsRepository;
use App\Repository\EmploiRepository;
use App\Repository\FormationRepository;
use App\Service\FormationFilesUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/formations")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/", name="formation")
     */
    public function index(Request $request,AdsRepository $adsRepository, EmploiRepository $emploiRepository, FormationRepository $formationRepository, PaginatorInterface $paginator): Response
    {
        $em=$this->getDoctrine()->getManager();
        
        $formation = $formationRepository->getFirstFormation();
        $restformation= $formationRepository->getRestFormation();
        $restformation=$paginator->paginate(
            $restformation,
            $request->query->getInt('page', 1),
            6
        );
        $expire= $formationRepository->findBy(
            ['multiimg'=>NULL],
            ['id'=>'ASC'],
        );
       
        if($expire){
            foreach ($expire as $k) {
                
                if (date('d/m/Y H:i:s') >= date($k->getDatelimit())) {
                    $em->remove($k);
                    $file=$k->getImg();
                    $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/files/emploi/'.$file;
                    $filesystem = new Filesystem();
                    $filesystem->remove($publicDirectory);
                    $em->flush();
                }
            }
            
        }
        
        $counted=$emploiRepository->getCounted();
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        return $this->render('formation/index.html.twig',[
            'homeActive' => 'formation',
            'formation' => $formation,
            'formations'=>$restformation,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    
    /**
     * @Route("/formation/add", name="addformation")
     */
    public function addFormation(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, FormationFilesUploader $formationFilesUploader): Response
    {
        $formation= new Formation();
        $form=$this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $formation=$form->getData();
            $datepost= $formation->getDatepost();
            $datelimit= $formation->getDatelimit();
            $multi=$form['multiimg']->getData();
            $img=$form['img']->getData();
            if ($multi) {
                foreach ($multi as $k) {
                    $multiimg[]=$formationFilesUploader->upload($k);  
                 }
                 $formation->setMultiimg($multiimg); 
            }
            else
                $formation->setMultiimg(null);
            
            if($img){
                $imgu=$formationFilesUploader->upload($img);
                $formation->setImg($imgu);
            }

            
            $datepost= date($datepost.' H:i:s ');
            $datelimit= date($datelimit.' H:i:s');
            // dd($datedeb,$datefin);
            
            $formation->setDatepost($datepost);
            $formation->setDatelimit($datelimit);

            //  dd($formation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            $this->addFlash("success", "Formation ajoutée avec succès!");
            return $this->redirectToRoute('addformation');
        }
        
        $counted=$emploiRepository->getCounted();

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            

        return $this->render('formation/addformation.html.twig',[
            'homeActive' => 'options',
            'typeActive' => 'addformation',
            'form'=>$form->createView(),
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }


    /**
     * @Route("/view/{theme}", name="viewformation")
     */
    public function showByFormation(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, FormationRepository $formationRepository): Response
    {
        if ($request->get('theme')) {
           $theme=$request->get('theme');
        }

        $formation = $formationRepository->findOneBy(['theme'=>$theme]);
        
        $counted=$emploiRepository->getCounted();

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        return $this->render('formation/formation.html.twig',[
            'homeActive' => 'formation',
            'formation' => $formation,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }


}

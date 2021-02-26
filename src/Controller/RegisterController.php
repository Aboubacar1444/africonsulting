<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CandidateType;
use App\Form\RecruteurType;
use App\Repository\AdsRepository;
use App\Repository\EmploiRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/register")
 */
class RegisterController extends AbstractController
{
    /**
     * @Route("/", name="register")
     */
    public function index(Request $request, AdsRepository $adsRepository, EmploiRepository $emploiRepository, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader): Response
    {
        
        $user= new User();
        $formcandidate=$this->createForm(CandidateType::class, $user);
    
        $formcandidate->handleRequest($request);
        if ($formcandidate->isSubmitted() && $formcandidate->isValid()){
                $user=$formcandidate->getData();
                $cv=$formcandidate['cv']->getData();
               if ($cv) {
                  $cvu=$fileUploader->upload($cv);
                  $user->setCV($cvu);
               }
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setRoles(['ROLE_CANDIDAT', 'ROLE_USER']);
                // dd($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                    

                    // $userRegisterEvent = new UserRegisterEvent($user);
                    // $eventDispatcherInterface->dispatch($userRegisterEvent,UserRegisterEvent::NAME);

                    $this->addFlash("success", "Votre compte a été créé avec succès!");
                    return $this->redirectToRoute('register');
                    

        }
        // elseif($formcandidate->isSubmitted()){
        //     if(!$user->getId()){
        //         $this->addFlash("error", "Votre compte n'a pas été créé il y a des erreurs dans vos saisies..");
        //         return $this->redirectToRoute('register');
               
        //     }
        // }
        $usere= new User();
        $formrecrutor=$this->createForm(RecruteurType::class, $usere);
    
        $formrecrutor->handleRequest($request);
        if ($formrecrutor->isSubmitted() && $formrecrutor->isValid()) {
                $usere = $formrecrutor->getData();
                $img=$formrecrutor['img']->getData();
                if ($img) {
                   $imgu=$fileUploader->upload($img);
                   $usere->setImg($imgu);
                }
                $passwordr = $passwordEncoder->encodePassword($usere, $usere->getPassword());
                $usere->setPassword($passwordr);
                $usere->setRoles(['ROLE_RECRUTEUR', 'ROLE_USER']);
                $em = $this->getDoctrine()->getManager();
                 $em->persist($usere);
                 $em->flush();
                    

                    // $userRegisterEvent = new UserRegisterEvent($user);
                    // $eventDispatcherInterface->dispatch($userRegisterEvent,UserRegisterEvent::NAME);

                    $this->addFlash("success", "Votre compte a été créé avec succès!");
                    return $this->redirectToRoute('register');
                    

        }
        
      
        $counted=$emploiRepository->getCounted();

        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        return $this->render('register/index.html.twig', [
            'homeActive'=> 3,
            'form'=>$formcandidate->createView(),
            'formr'=>$formrecrutor->createView(),
            'counted'=> $counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }
}

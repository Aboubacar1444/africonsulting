<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AdsRepository;
use App\Repository\EmploiRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/signin")
 */
class SigninController extends AbstractController
{
    private $userRepository;
    private $passwordEncoder;
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository=$userRepository;
        $this->passwordEncoder=$passwordEncoder;
    }
    
    /**
     * @Route("/", name="signin")
     */
    public function index(AuthenticationUtils $authenticationUtils, AdsRepository $adsRepository, EmploiRepository $emploiRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $counted=$emploiRepository->getCounted();
        
        $ads=$adsRepository->getPub();
        $part=$adsRepository->getPart();
            
        return $this->render("signin/index.html.twig",[
            'homeActive'=>4,
            'last_username' => $lastUsername, 
            'error' => $error,
            'counted'=>$counted,
            'ads'=>$ads,
            'part'=>$part,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/createadmin", name="createadmin")
     */
    public function createAdmin(Request $request): Response
    {
            $checkAdmin=$this->userRepository->findAll();
            // dd($checkAdmin);
            //$localites=$this->localite->findAll();
            $User=new User();
            $em=$this->getDoctrine()->getManager();
            if($request->isMethod('POST')){
                $data=$request->request->all();
                $password = $this->passwordEncoder->encodePassword($User,$data['password']);
    
                $User->setForename($data['forename']);
                $User->setName($data['name']);
                $User->setFonction($data['job']);
                $User->setEmail($data['email']);
                $User->setRoles([$data['type']]);
                // if ($request->get('academie')) {
                //     $findAcademie=$this->academie->findOneBy(['id'=>$data['academie']]);
                //     $adminAgent->setAcademie($findAcademie);
                // }
                $User->setRoles($User->getRoles());
                $User->setPassword($password);
                //  dd($adminAgent->getRoles());
                    $em->persist($User);
                    $em->flush();
                // $findAgent=$this->agentRepository->findOneBy(['id'=>$adminAgent->getId()]);
                // $User->setAgent($findAgent);
                // $User->setEmail($adminAgent->getEmail());
                // $User->setRoles($adminAgent->getRoles());
                // $User->setPassword($adminAgent->getPassword());
                //     $em->persist($User);
                //     $em->flush();
    
    
            }
            // dd($checkAdmin);
            return $this->render('dashboards/admin/createadmin.html.twig',[
                "admindatas"=>$checkAdmin,
                "homeActive"=>false,
            ]);
    }

    /**
     * @Route("/forgotpassword/{token}", name="forgotpassword")
     */
    public function forgotPassword(Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
       
        if($request->get('email')){
            $email=$request->get('email');
            $PassUser=$this->userRepository->findOneBy(['email'=>$email]);
        }
        
        if(!$PassUser){
            $this->addFlash('error','Adresse E-mail invalide.');
            return $this->redirectToRoute('signin');
        }else{
            $token=$request->get('token');
            if($this->isCsrfTokenValid('forgotpassword',$token)){
                if($request->isMethod("POST")){
                    $newpass=$request->get('newpass');
                    $User=new User();
                    $changedpass = $this->passwordEncoder->encodePassword($User,$newpass);

                    $PassUser->setPassword($changedpass);
                    $em->flush();
                    $this->addFlash('success','Mot de passe mise à jour avec succèss.');
                    return $this->redirectToRoute('signin');

                }
            }
        }

        return $this->render('signin/forgotpassword.html.twig',[
          'passuser'=>$PassUser,
          "homeActive"=>false,
        ]);
    }

}

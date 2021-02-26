<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\LocaliteRepository;
use App\Repository\PaysRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    private $userRepository;
    private $passwordEncoder;
    private $paysRepository;
    private $localiteRepository;
    public function __construct(UserRepository $userRepository, PaysRepository $paysRepository, LocaliteRepository $localiteRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository=$userRepository;
        $this->passwordEncoder=$passwordEncoder;
        $this->paysRepository=$paysRepository;
        $this->localiteRepository=$localiteRepository;
    }
    
    
    /**
     * @Route("/", name="admin")
     */
    public function index(Request $request): Response
    {
        $checkAdmin=$this->userRepository->findAll();
            // dd($checkAdmin);
            $pays=$this->paysRepository->findAll();
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
                if ($request->get('localite')) {
                    $findLocalite=$this->localiteRepository->findOneBy(['id'=>$data['localite']]);
                    $User->setLocalite($findLocalite);
                }
                $User->setRoles($User->getRoles());
                $User->setPassword($password);
               
                $em->persist($User);
                $em->flush();
                
    
            }
            // dd($checkAdmin);
            return $this->render('dashboards/admin/createagents.html.twig',[
                "admindatas"=>$checkAdmin,
                "pays"=>$pays,
                "homeActive"=>"createagent",
            ]);
    }
}

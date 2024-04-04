<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginType;
use function mysql_xdevapi\getSession;
use App\Repository\loginTimeRepository;
use App\Entity\loginTime ;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController
{    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

       if(!$this->getUser()){
        $user = new User();
        $form = $this->createForm(LoginType::class,$user);
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastusername ();
        return $this->render('user/login.html.twig', ['last_username'=>$lastUsername,'error'=>$error,'f' =>$form->createView()]);
       }else{
        return $this->redirectToRoute('index');
       }

    }

  
    /**
     * @Route("/updatetime", name="updatetime")
     */
    public function updatetime(loginTimeRepository $a)
    {          
 
        $rep = $this->getDoctrine()->getManager();
       if($loginTime =$a->loginend($this->session->get('logintime')))
        return $this->redirectToRoute('app_login');

    }
    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {  
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
        
    }
    
}

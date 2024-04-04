<?php
/* /src/Security/BackendAuthenticator.php */
namespace App\Security;

use App\Entity\User; 
use App\Entity\loginTime ;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Security\EmailVerifier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Authenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private EmailVerifier $emailVerifier;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder,EmailVerifier $emailVerifier,SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->emailVerifier = $emailVerifier;
        $this->session = $session;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        

        
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email introuvable');
        }
        if ($user->getdisable()==true){

            throw new CustomUserMessageAuthenticationException('U have been blocked !');
        }
        $recaptcha = $_POST['g-recaptcha-response'];
        $res = $this->reCaptcha($recaptcha);
        if(!$res['success'])
        {
            throw new CustomUserMessageAuthenticationException('are u a robot');
   
        }

    
 
        return $user;
    }
    public function reCaptcha($recaptcha){
        $secret = "6LfC8KwfAAAAALnnznx7jJhJ4PftilUFHekIIUTg";
        $ip = $_SERVER['REMOTE_ADDR'];
      
        $postvars = array("secret"=>$secret, "response"=>$recaptcha, "remoteip"=>$ip);
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        $data = curl_exec($ch);
        curl_close($ch);
      
        return json_decode($data, true);
      }
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Mot de passe incorrecte');
        }

        
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);

   

    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
         return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        
        
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        $user = $token->getUser();


       
       
       if($user->isVerified()==false) {
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('beyondtravel4@gmail.com', 'beyond travel'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            return new RedirectResponse($this->urlGenerator->generate('app_logout'));
           
             
        }else{

            if($user->getroles() == ['ROLE_CLIENT','ROLE_ADMIN']){
                return new RedirectResponse($this->urlGenerator->generate('list'));
            }   
            else {
                $loginTime = new loginTime ();
                $loginTime->setUser($user);
                $this->session->set('logintime',$loginTime->setTokenverif());
     
                $loginTime->setFirstlogin();
                 $this->entityManager->persist($loginTime);
                 $this->entityManager->flush();
                return new RedirectResponse($this->urlGenerator->generate('index'));
            
           }



        }

        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
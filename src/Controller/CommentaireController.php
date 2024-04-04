<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentaireRepository;
use App\Repository\HotelRepository;
use App\Form\CommentaireType;
use App\Entity\Commentaire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity\Hotel;


use App\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends AbstractController
{


    
    /**
     * @Route("/commentaireFront", name="app_comm")
     */
    public function index(CommentaireRepository $repo): Response
    {   $commentaires = $repo->findBy(array('isPublished'=>'0'),array('id'=>'DESC'));
        return $this->render('commentaire/listCommentaire.html.twig', array("commentaires" => $commentaires));
    }
       /**
     * @Route("/commentaire", name="app_commentaire")
     */
    public function list(CommentaireRepository $repo): Response
    {   $commentaires = $repo->findBy(array('isPublished'=>'0'),array('created_at'=>'DESC'));
        return $this->render('commentaire/index.html.twig', array("commentaires" => $commentaires));
    }
    /**
     * @Route("/addcommentaire", name="addComm")
     */

    public function add(Request $request)
    {   $user = new User();
        $hotel=$this->getDoctrine()->getRepository(Hotel::class)->find(1);
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $commentaire->setHotel($hotel)
                ->setUser($user)
                ->setCreatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render("commentaire/add.html.twig",array('form'=>$form->createView()));
    }
      /**
     * @Route("/updatecommentaire/{id}", name="updateComm")
     */
    public function update($id, Request $request): Response
    {
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_commentaire');
        }
        return $this->render("commentaire/update.html.twig",array('form'=>$form->createView()));
    }
      /**
     * @Route("/deletecommentaire/{id}", name="deleteComm" )
     */
    public function delete($id ,MailerInterface $mailer)
    {
        
        
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();

        $email = (new Email())
        ->from('Dalicharfi8@gmail.com')
        ->to($commentaire->getUser()->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Notification BeyondTravel')
        ->text('Salut Votre Commentaire sur le site BeyondTravel a été supprimée par l admin du site  ');
     

        $mailer->send($email);

        return $this->redirectToRoute('app_commentaire');
        
    }
         /**
     * @Route("/validerrcommentaire/{id}", name="validerComm" )
     */
    public function valider($id, MailerInterface $mailer)
    {
        
        
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $commentaire->setIsPublished(true);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

     //   $email = (new Email())
     //   ->from('Dalicharfi8@gmail.com')
      //  ->to($commentaire->getUser()->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
      //  ->subject('Validation Commentaire')
     //   ->text('votre Commentaire a été validé avec succées , Vérifier');
       

  //   $mailer->send($email);



        return $this->redirectToRoute('app_commentaire');
        
    }
    /**
     * @Route("/commentairem", name="app_commentairee")
     */
    public function list3(CommentaireRepository $repo): Response
    {   $commentaires = $repo->findBy(['isPublished'=>'1','hotel'=> 1] );
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($commentaires);
         
     
      
        return new JsonResponse($formatted);
    }
     /**
     * @Route("/deletecomm/{id}", name="deleteCommentaire" )
     */
    public function deleteC($id ,MailerInterface $mailer)
    {
        
        
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();

       

        

        return $this->redirectToRoute('hotels_front');
        
    }

    //*****MOBILE

    /**
     * @Route("/commentaire/mobile/aff", name="affmobcom")
     */
    public function affmobcom(NormalizerInterface $normalizer)
    {
        $med=$this->getDoctrine()->getRepository(Commentaire::class)->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($med) {
            return $med->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($med);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/commentaire/mobile/affhotel", name="affmobpcomHotel")
     */
    public function affmobpcomHotel(Request $request,NormalizerInterface $normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $hotel = $em->getRepository(Hotel::class)->find($request->get('id'));

        $avis=$this->getDoctrine()->getRepository(Commentaire::class)->findBy([
            'hotel' => $hotel,
            'isPublished' => true
        ]);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($avis) {
            return $avis->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($avis);
        return new JsonResponse($formatted);    }

    /**
     * @Route("/commentaire/mobile/new", name="addmobcom")
     */
    public function addmobcom(Request $request,NormalizerInterface $normalizer,EntityManagerInterface $entityManager,UserRepository  $userRepository)
    {
        $em=$this->getDoctrine()->getManager();

        $com= new Commentaire();

        $user = $em->getRepository(User::class)->find($request->get('iduser'));
        $hotel = $em->getRepository(Hotel::class)->find($request->get('idhotel'));

        $com->setHotel($hotel);
        $com->setUser($user);
        $com->setText($request->get('text'));
        $com->setCreatedAt(new \DateTime('now'));

        $com->setIsPublished(false);

        $entityManager->persist($com);
        $entityManager->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($com) {
            return $com->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($com);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/commentaire/mobile/edit", name="editmobcom")
     */
    public function editmobcom(Request $request,NormalizerInterface $normalizer)
    {   $em=$this->getDoctrine()->getManager();

        $com = $em->getRepository(Commentaire::class)->find($request->get('id'));
        $com->setText($request->get('text'));
        $com->setIsPublished($request->get('ispublish'));

        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($com) {
            return $com->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($com);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/commentaire/mobile/del", name="delmobcom")
     */
    public function delmobcom(Request $request,NormalizerInterface $normalizer)
    {           $em=$this->getDoctrine()->getManager();
        $com=$this->getDoctrine()->getRepository(Commentaire::class)
            ->find($request->get('id'));
        $em->remove($com);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($com) {
            return $com->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($com);
        return new JsonResponse($formatted);

    }
}

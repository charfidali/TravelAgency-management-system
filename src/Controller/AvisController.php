<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Form\AvisType;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Hotels;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\AvisRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class AvisController extends AbstractController
{
    /**
         * @Route("/avis", name="app_avis")
     */
    public function index(AvisRepository $r): Response
    {  $avis=$r->findall();
        return $this->render('avis/index.html.twig', [
            
            'avis'=> $avis
        ]);
    }
     /**
     * @Route("/addavis/{id}", name="avis_add")
     */

    public function add(UserRepository $rep,Request $request, int $id)
    {   
        
        $user= $this->getUser();

        $hotel=$this->getDoctrine()->getRepository(Hotels::class)->find($id);
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $avis->setHotel($hotel)
                ->setUser($user);
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('hotels_front');
        }
        return $this->render("avis/add.html.twig",[
        'form'=>$form->createView(),
        'hotel' => $hotel
   ]);
    }
          /**
     * @Route("/deleteavis/{id}", name="deleteavis" )
     */
    public function delete($id )
    {
        
        
        $avis = $this->getDoctrine()->getRepository(Avis::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($avis);
        $em->flush();

  

        return $this->redirectToRoute('app_avis');
        
    }
    //*****MOBILE

    /**
     * @Route("/avis/mobile/aff", name="affmobpavis")
     */
    public function affmobpavis(NormalizerInterface $normalizer)
    {
        $avis=$this->getDoctrine()->getRepository(Avis::class)->findAll();
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
     * @Route("/avis/mobile/affhotel", name="affmobpavisHotel")
     */
    public function affmobpavisHotel(Request $request,NormalizerInterface $normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $hotel = $em->getRepository(Hotel::class)->find($request->get('id'));

        $avis=$this->getDoctrine()->getRepository(Avis::class)->findBy([
            'hotel' => $hotel
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
     * @Route("/avis/mobile/new", name="addmobavis")
     */
    public function addmobavis(Request $request,NormalizerInterface $normalizer,EntityManagerInterface $entityManager)
    {
        $em=$this->getDoctrine()->getManager();
        $avis= new Avis();
        $user = $em->getRepository(User::class)->find($request->get('iduser'));
        $hotel = $em->getRepository(Hotel::class)->find($request->get('idhotel'));

        $avis->setRate($request->get('rate'));
        $avis->setHotel($hotel);
        $avis->setUser($user);

        $entityManager->persist($avis);
        $entityManager->flush();

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
     * @Route("/avis/mobile/edit", name="editmobavis")
     */
    public function editmobavis(Request $request,NormalizerInterface $normalizer)
    {   $em=$this->getDoctrine()->getManager();

        $avis = $em->getRepository(Avis::class)->find($request->get('id'));
        $avis->setRate($request->get('rate'));


        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($avis) {
            return $avis->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($avis);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/avis/mobile/del", name="delmobavis")
     */
    public function delmobavis(Request $request,NormalizerInterface $normalizer)
    {           $em=$this->getDoctrine()->getManager();
        $avis=$this->getDoctrine()->getRepository(Avis::class)
            ->find($request->get('id'));
        $em->remove($avis);
        $em->flush();
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


}
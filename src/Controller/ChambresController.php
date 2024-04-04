<?php

namespace App\Controller;

use App\Entity\Chambres;
use App\Form\Chambres1Type;
use App\Repository\ChambresRepository;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


class ChambresController extends AbstractController
{
    /**
     * @Route("/chambres", name="display_chambres")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $chambre = $this->getDoctrine()->getManager()->getRepository(Chambres::class)->findAll();
        $chambre = $paginator->paginate(
            $chambre, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('chambres/index.html.twig', [
            'c'=>$chambre
        ]);
    }

   

     /**
     * @Route("/addChambres", name="addChambres")
     */
    public function addChambres(Request $request): Response
    {
        $chambre = new Chambres();

        $form = $this->createForm(Chambres1Type::class,$chambre);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            $file = $form->get('image')->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $filename);
            $chambre->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($chambre);
            $em->flush();

            return $this->redirectToRoute('display_chambres');
    }
        return $this->render('chambres/new.html.twig',['c'=>$form->createView()]);
    }


   
    /**
     * @Route("/removechambres/{id}", name="supp_Chambres")
     */
    public function suppressionChambres(Chambres  $chambre): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($chambre);
        $em->flush();

        return $this->redirectToRoute('display_chambres');


    }

    /**
     * @Route("/reservation{id}", name="reservation_chambre", methods={"GET"})
     */
    public function show(Request $request , Chambres $chambre ): Response
    {
       //$id = $request->get('id');
        //$hotels = $hotelsRepository->find(7);
       //dd($hotels->getChambre());

        return $this->render('chambres/myPdf.html.twig', [
            'chambre' => $chambre,
        ]);
    }
    


    /**
     * @Route("/modifchambres/{id}", name="modifchambres")
     */
    public function modifchambres(Request $request,$id): Response
    {
        $chambre = $this->getDoctrine()->getManager()->getRepository(Chambres::class)->find($id);

        $form = $this->createForm(Chambres1Type::class,$chambre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_chambres');
        }
        return $this->render('chambres/edit.html.twig',['c'=>$form->createView()]);




    }


    
}

<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


    
class VoitureController extends AbstractController
{
    /**
     * @Route("/voiture", name="display_voiture")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $voiture = $this->getDoctrine()->getManager()->getRepository(Voiture::class)->findAll();
        $voiture = $paginator->paginate(
            $voiture, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('voiture/index.html.twig', [
            'v'=>$voiture
        ]);
    }

    /**
     * @Route("/statvoiture", name="statvoiture")
     */
    public function indexAction(){
        $repository = $this->getDoctrine()->getRepository(Voiture::class);
        $voiture = $repository->findAll();
        $Suv=0;
        $lux=0;
        $eco=0;
        $touristique=0;
        foreach ($voiture as $voiture)
        {
            if ($voiture->getType()=='Suv')  
                $Suv+=1;
            if ($voiture->getType()=='lux')  
                $lux+=1;
            if ($voiture->getType()=='eco')  
                $eco+=1;
            if ($voiture->getType()=='touristique')  
                $touristique+=1;

          
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['', ''],
                ['Suv',     $Suv],
                ['lux',      $lux],
                ['eco',      $eco],
                ['touristique',      $touristique],
                

            ]
        );
        $pieChart->getOptions()->setTitle('les voitures par type');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('voiture/stats.html.twig', array('piechart' => $pieChart));    
    } 


     /**
     * @Route("/searchVoiture", name="searchVoiture")
     */

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $voiture =  $em->getRepository(Voiture::class)->findEntitiesByString($requestString);
        
        
        if(!$voiture) {
            $result['voiture']['error'] = "Voiture introuvable :( ";
        } else {
            $result['voiture'] = $this->getRealEntities($voiture);
        }
        return new Response(json_encode($result));
        
    }
    public function getRealEntities($voiture){
        foreach ($voiture as $voiture){
            $realEntities[$voiture->getId()] = [$voiture->getImage(),$voiture->getMatricule(),$voiture->getMarque(),$voiture->getModel(),$voiture->getNbsieges(),$voiture->getType(),$voiture->getPrix(),$voiture->getId()];

        }
        return $realEntities;
    }

    /**
     * @Route("/voiturefront", name="voiture_front")
     */
    public function indexfront(Request $request, PaginatorInterface $paginator): Response
    {
       


        $voiture = $this->getDoctrine()->getManager()->getRepository(Voiture::class)->findAll();
        $voiture = $paginator->paginate(
            $voiture, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('voiture/voiturefront.html.twig', [
            'v'=>$voiture
        ]);
    }

    /**
     * @Route("/addVoiture", name="addVoiture")
     */
    public function addVoiture(Request $request): Response
    {
        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class,$voiture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $file = $form->get('image')->getData();


            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $filename);
            $voiture->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($voiture);
            $em->flush();

            return $this->redirectToRoute('display_voiture');
        }
        return $this->render('voiture/createVoiture.html.twig',['v'=>$form->createView()]);
    }

      
    /**
     * @Route("/voiturefront{id}", name="Voiture_singel", methods={"GET"})
     */
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    /**
     * @Route("/removeVoiture/{id}", name="supp_voiture")
     */
    public function suppressionVoiture(Voiture  $voiture): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($voiture);
        $em->flush();

        return $this->redirectToRoute('display_voiture');


    }


    /**
     * @Route("/modifvoiture/{id}", name="modifvoiture")
     */
    public function modifvoiture(Request $request,$id): Response
    {
        $voiture = $this->getDoctrine()->getManager()->getRepository(Voiture::class)->find($id);

        $form = $this->createForm(VoitureType::class,$voiture);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_voiture');
        }
        return $this->render('voiture/updatevoiture.html.twig',['v'=>$form->createView()]);




    }



    
}   

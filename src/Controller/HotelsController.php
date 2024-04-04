<?php

namespace App\Controller;

use App\Entity\Hotels;
use App\Form\HotelsType;
use App\Repository\HotelsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use App\Form\SearchAvisType;
use App\Form\CommentaireType;

use App\Entity\Commentaire;





class HotelsController extends AbstractController
{

   

    /**
     * @Route("/hotels", name="display_hotels")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $hotels = $this->getDoctrine()->getManager()->getRepository(Hotels::class)->findAll();
        $hotels = $paginator->paginate(
            $hotels, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('hotels/index.html.twig', [
            'h'=>$hotels
        ]);
    }

          
 /**
     * @Route("/stathotels", name="stathotel")
     */
    public function indexAction(){
        $repository = $this->getDoctrine()->getRepository(Hotels::class);
        $hotels = $repository->findAll();
        $cinque=0;
        $quatre=0;
        $trois=0;
        $deux=0;
        $un=0;
        foreach ($hotels as $hotels)
        {
            if ($hotels->getnbetoiles()=='5')  
                $cinque+=1;
            if ($hotels->getnbetoiles()=='4')  
                $quatre+=1;
            if ($hotels->getnbetoiles()=='3')  
                $trois+=1;
            if ($hotels->getnbetoiles()=='2')  
                $deux+=1;
            if ($hotels->getnbetoiles()=='1')  
                $un+=1;

          
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['', ''],
                ['5',     $cinque],
                ['4',      $quatre],
                ['3',      $trois],
                ['2',      $deux],
                ['1',      $un],

            ]
        );
        $pieChart->getOptions()->setTitle('hotels par etoiles');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('hotels/stats.html.twig', array('piechart' => $pieChart));    
    } 

    /**
     * @Route("/searchHotel", name="searchHotel")
     */

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $hotels =  $em->getRepository(Hotels::class)->findEntitiesByString($requestString);
        
        
        if(!$hotels) {
            $result['hotels']['error'] = "Hotel introuvable :( ";
        } else {
            $result['hotels'] = $this->getRealEntities($hotels);
        }
        return new Response(json_encode($result));
        
    }
    public function getRealEntities($hotels){
        foreach ($hotels as $hotels){
            $realEntities[$hotels->getId()] = [$hotels->getImage(),$hotels->getNom(),$hotels->getAdresse(),$hotels->getNbetoiles(),$hotels->getPointfort(),$hotels->getId()];

        }
        return $realEntities;
    }

    /**
     * @Route("/hotelfront", name="hotels_front")
     */
    public function indexfront(Request $request, PaginatorInterface $paginator): Response
    {
        $hotels = $this->getDoctrine()->getManager()->getRepository(Hotels::class)->findAll();
        $hotels = $paginator->paginate(
            $hotels, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('hotels/hotelsfront.html.twig', [
            'h'=>$hotels
        ]);
    }

     /**
     * @Route("/addHotels", name="addHotels")
     */
    public function addHotels(Request $request): Response
    {
        $hotels = new Hotels();
        $form = $this->createForm(HotelsType::class,$hotels);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            $file = $form->get('image')->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $filename);
            $hotels->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($hotels);
            $em->flush();

            return $this->redirectToRoute('display_hotels');
    }
        return $this->render('hotels/createHotels.html.twig',['f'=>$form->createView()]);
    }

     
    /**
     * @Route("/hotelfront{id}", name="Hotels_singel", methods={"GET"})
     */
    public function show(Request $request , Hotels $hotels ): Response
    {
       //$id = $request->get('id');
        //$hotels = $hotelsRepository->find(7);
       //dd($hotels->getChambre());

        return $this->render('hotels/show.html.twig', [
            'hotels' => $hotels,
        ]);
    }
    


   
    /**
     * @Route("/removeHotels/{id}", name="supp_Hotels")
     */
    public function suppressionHotels(Hotels  $hotels): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($hotels);
        $em->flush();

        return $this->redirectToRoute('display_hotels');


    }


    /**
     * @Route("/modifhotels/{id}", name="modifhotels")
     */
    public function modifhotels(Request $request,$id): Response
    {
        $hotels = $this->getDoctrine()->getManager()->getRepository(hotels::class)->find($id);

        $form = $this->createForm(HotelsType::class,$hotels);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_hotels');
        }
        return $this->render('hotels/updateHotels.html.twig',['f'=>$form->createView()]);


       



   
    }
       /**
     * @Route("/show/{id}", name="hotel_show")
     * 
     */
    
    public function show1(int $id,CommentaireRepository $repo,HotelsRepository $repo1, UserRepository $rep, Request $request)
    {  

        
        $user= $this->getUser();
        $hotels=$repo1->findall();
        
        $hotel=$repo1->find($id);
        $img=$hotel->getImage();
        // Nous créons l'instance de "Commentaires"
        $commentaire = new Commentaire();
        $commentaire1 = new Commentaire();

        $commentaires = $repo->findBy(['isPublished'=>'1','hotel'=> $id] );
        // Nous créons le formulaire en utilisant "CommentairesType" et on lui passe l'instance
        $form1 = $this->createForm(SearchAvisType::class, $commentaire);
        $form = $this->createForm(CommentaireType::class, $commentaire);

        // Nous récupérons les données
        $form->handleRequest($request);

        // Nous vérifions si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()){
            // Hydrate notre commentaire avec l'article
            $commentaire->setHotel($hotel)
                ->setUser($user)
                ->setCreatedAt(new \DatetimeImmutable('now'))
                ->setIsPublished(false);
            $doctrine = $this->getDoctrine()->getManager();

            // On hydrate notre instance $commentaire
            $doctrine->persist($commentaire);

            // On écrit en base de données
            $doctrine->flush();
            return $this->redirectToRoute('hotel_show', ['id' => $hotel->getId()]);
        }
        $form1->handleRequest($request);
        if ($form1->isSubmitted()){
          $date=  $form1["created_at"]->getData();
          

             $commentaires = $repo->findBy([array('isPublished'=>'1'),
                array("created_at" => '$date')

            ]);
            var_dump($form1); die;
               
        }


        return $this->render('hotels/details.html.twig', [
            'commentaires' => $commentaires,
            'hotel' => $hotel,
            'hotels' => $hotels,
            'user'=>$user,
            'img'=>$img,

            'form' => $form->createView(),
            'form1' => $form1->createView(),

        ]);
    }

    

   

    
}

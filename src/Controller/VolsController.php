<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Vols;
use App\Form\VolsType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use App\Repository\VolsRepository;
//use phpDocumentor\Reflection\DocBlock\Serializer;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use DateTime ;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;

use Symfony\Component\Serializer\Encoder ;
class VolsController extends AbstractController
{



    /**
     * @Route("/search", name="search")
     */
      public function recherche(Request $request, VolsRepository $repo, PaginatorInterface $paginator) {

        

                  $searchForm = $this->createForm(VolsType::class);
                  $searchForm->handleRequest($request);

                  $donnees = $repo->findAll();

                  if ($searchForm->isSubmitted() && $searchForm->isValid()) {

                      $Destination_aller = $searchForm->getData()->getDestination_aller();

                      $donnees = $repo->search($Destination_aller);

                      if ($donnees == null) {
                          $this->addFlash('erreur', 'Aucun vols contenant ce mot clé dans le titre n\'a été trouvé, essayez en un autre.');
                      }

                  }

                  // Paginate the results of the query
                  $vol = $paginator->paginate(
                  // Doctrine Query, not results
                      $donnees,
                      // Define the page parameter
                      $request->query->getInt('page', 1),
                      // Items per page
                      4
                  );

                  return $this->render('vols/search.html.twig',[

                      'vols' => $vol,
                      'searchForm' => $searchForm->createView()
                  ]);
              }

          
  /**
 *
 * @Route("/", name="vols_index")
 */
    public function index2(Request $request): Response
    {

        // Retrieve the entity manager of Doctrine
        $data=$request->get('data');
        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an vols entity
        $VolsRepository = $em->getRepository(Vols::class);

        // Find all the data on the vols table, filter your query as you need
        $vol = $VolsRepository->createQueryBuilder('vols')
            ->orWhere('Vols.name Like :name ')
            ->setParameter('voyage', '%'.$data.'%')
            ->getQuery();

        return $this->render('vols/index.html.twig', [
            'vols' => $vol,
        ]);
    } 



    /******************Ajouter vols*****************************************/
    /**
     * @Route("/addvolscode", name="add_volss")
     * @Method("POST")
     */

    public function ajoutervolsAction(Request $request)
    {
        $vols = new Vols();
        $destination_aller = $request->query->get("destination_aller");
        $destination_retour = $request->query->get("destination_retour");
        $voyage = $request->query->get("voyage");
        $em = $this->getDoctrine()->getManager();
        $date_depart = new \DateTime('now');
        $date_retour = new \DateTime('now');
        $passagers = $request->query->get("passagers");
        $cabine = $request->query->get("cabine");



        $vols->setDestinationAller($destination_aller);
        $vols->setDestinationRetour($destination_retour);
        $vols->setVoyage($voyage);
        $vols->setDateDepart($date_depart);
        $vols->setDateRetour($date_retour);
        $vols->setPassagers($passagers);
        $vols->setCabine($cabine);

        $em->persist($vols);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($vols);
        return new JsonResponse($formatted);

    }
    /******************Supprimer Reclamation*****************************************/

    /**
     * @Route("/deletevolscode", name="delete_volscode")
     * @Method("DELETE")
     */

    public function deletevolscode(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $vols = $em->getRepository(Vols::class)->find($id);
        if($vols!=null ) {
            $em->remove($vols);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("vols a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id vols invalide.");


    }
    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updatevoldscode", name="update_volscode")
     * @Method("PUT")
     */
    public function modifiervolsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $vols = $this->getDoctrine()->getManager()
            ->getRepository(Vols::class)
            ->find($request->get("id"));

        $vols->setDestinationAller($request->get("destination_aller"));
        $vols->setDestinationRetour($request->get("destination_retour"));
        $vols->setVoyage($request->get("Voyage"));
        $vols->setPassagers($request->get("Passagers"));
        $vols->setCabine($request->get("Cabine"));

        $em->persist($vols);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($vols);
        return new JsonResponse("vols a ete modifiee avec success.");

    }

    /******************Detail vols*****************************************/

    /**
     * @Route("/detailvols", name="detail_vols")
     * @Method("GET")
     */

    //Detail vols
    public function detailvolsAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $vols = $this->getDoctrine()->getManager()->getRepository(Vols::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDestination_aller();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($vols);
        return new JsonResponse($formatted);
    }



    /**
     * @Route("/showcode", name="app_vols_showw")
     */
    public function showcode()
    {
        $vols =$this->getDoctrine()->getManager()->getRepository(vols::class)->findall() ;
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $formatted =$serializer->normalize($vols) ;
        return new JsonResponse($formatted) ;


    }

    /**
     * @Route("/", name="app_vols_index", methods={"GET"})
     */
    public function index(VolsRepository $volsRepository): Response
    {
        return $this->render('vols/index.html.twig', [
            'vols' => $volsRepository->findAll(),
        ]);
    }


    /**
     * @Route("/admin", name="display_admin", methods={"GET"})
     */
    public function indexadmin(): Response
    {
        return $this->render('admin/index.html.twig'
        );
    }


    /**
     * @Route("/new", name="app_vols_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VolsRepository $volsRepository): Response
    {
        $vol = new Vols();
        $form = $this->createForm(VolsType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $volsRepository->add($vol);
            return $this->redirectToRoute('app_vols_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vols/new.html.twig', [
            'vol' => $vol,
            'form' => $form->createView(),
        ]);


    }


    /**
     * @Route("/show/{id}", name="app_vols_show", methods={"GET"})
     */
    public function show(Vols $vol): Response
    {
        return $this->render('vols/show.html.twig', [
            'vol' => $vol,
        ]);


    }



    /**
     * @Route("/showfront", name="app_vols_indexfront", methods={"GET"})
     */
    public function indexfront(VolsRepository $volsRepository): Response
    {
        return $this->render('vols/showfront.html.twig', [
            'vols' => $volsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_vols_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Vols $vol, VolsRepository $volsRepository): Response
    {
        $form = $this->createForm(VolsType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em= $this->getDoctrine()->getManager()->flush();
            $volsRepository->add($vol);
            return $this->redirectToRoute('app_vols_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vols/edit.html.twig', [
            'vol' => $vol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_vols_delete", methods={"POST"})
     */
    public function delete(Request $request, Vols $vol, VolsRepository $volsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vol->getId(), $request->request->get('_token'))) {
            $volsRepository->remove($vol);
        }

        return $this->redirectToRoute('app_vols_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/r/search_recc", name="search_recc", methods={"GET"})
     */
    public function search_rec(Request $request, NormalizerInterface $Normalizer): Response
    {

        $requestString = $request->get('searchValue');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT r FROM App\Entity\Vols r   where r.destination_aller like :suj OR r.destination_retour like :suj  '
        );
        $query->setParameter('suj', $requestString . '%');

        $Vol = $query->getResult();
        $jsoncontentc = $Normalizer->normalize(Vol, 'json', ['groups' => 'posts:read']);
        $jsonc = json_encode($jsoncontentc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }
    }


    /**
     * @Route("/calendar", name="calendar")
     */
    public function calendar()
    {
        $repository = $this->getDoctrine()->getRepository(vols::class);
        $event = $repository->findAll();
        $rdvs=[];
        foreach($event as $event){
            $rdvs[]=[
                'id' => $event->getId(),
                'destination_aller' => $event->getDestinationAller(),
                'destination_retour'=>$event->getDestinationRetour(),
                'title' => $event->getVoyage(),
                'start' => $event->getDateDepart()->format('Y-m-d'),
                'date_retour' => $event->getDateRetour()->format('Y-m-d'),
                'passagers' => $event-> getPassagers(),
                'cabine'=>$event->getCabine(),
            ];
        }
        $data= json_encode($rdvs);
        return $this->render('vols/Callender.html.twig', compact('data'));
    }
    /**
     * @Route("/api/{id}/edit", name="editCalendar", methods={"PUT"})
     */
    public function editCalendar(vols $calendar, Request $request)    {
        //récuperation de données
        $donnees=json_decode($request->getContent());

        if(
            isset($donnees->destination_aller) && !empty($donnees->destination_aller) &&
            isset($donnees->destination_retour) && !empty($donnees->destination_retour) &&
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->date_retour) && !empty($donnees->date_retour) &&
            isset($donnees->passagers) && !empty($donnees->passagers) &&
            isset($donnees->cabine) && !empty($donnees->cabine)

        ){
            //données completes, on initialise un code
            $code = 200;

            //verification de l'id existe
            if(!$calendar){
                //on instancie un rende
                $calendar = new vols;
                //change code
                $code=201;
            }
            //on hydrate l'objet avec les données
            $calendar->setDestinationAller($donnees->destination_aller);
            $calendar->setDestinationRetour($donnees->destination_retour);
            $calendar->setVoyage($donnees->title);
            $calendar->setDateDepart(new DateTime($donnees->start));
            $calendar->setDateRetour(new DateTime($donnees->date_retour));
            $calendar->setPassagers($donnees->passagers);
            $calendar->setCabine($donnees->cabine);

            $em=$this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();
            //return code
            return new Response('Ok', $code);
        }else{
            //données non completes
            return new Response('data not complete', 404);
        }

        return $this->render('vols/calenderEdit.html.twig');
    }
    /**
     * @Route("/stat", name="stati")
     */
    public function indexAction(){
        $repository = $this->getDoctrine()->getRepository(vols::class);
        $vols = $repository->findAll();
        $co=0;
        $rea=0;
        $his=0;
        foreach ($vols as $vols)
        {
            if ($vols->getDestinationAller()=='tunis')  :
                $co+=1;
            elseif ($vols->getDestinationAller()=='ghana')  :

                $rea+=1;
            else :
                $his +=1;

            endif;
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['', ''],
                ['tunis',     $co],
                ['ghana',      $rea],
                ['france',      $his],

            ]
        );
        $pieChart->getOptions()->setTitle('Vols par aller');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('vols/stats.html.twig', array('piechart' => $pieChart));
    }
    /**
     * @Route("/", name="vols_index")
     */
    public function search(Request $request, PaginatorInterface $paginator): Response
    {

        $data=$request->get('data');
        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an Concerts entity
        $VolsRepository = $em->getRepository(Vols::class);
        // Find all the data on the Concerts table, filter your query as you need
        $allVolsQuery = $VolsRepository->createQueryBuilder('vols')
            ->andWhere('vols.destination_aller Like :destination_aller ')

            ->setParameter('destination_aller', '%'.$data.'%')

            ->getQuery();
        // Paginate the results of the query
        $vols = $paginator->paginate(
        // Doctrine Query, not results
            $allVolsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3);
        return $this->render('vols/showfront.html.twig', [
            'vols' => $vols,
        ]);
    }
    /**
     * @Route("/testri", name="testri")
     */
    public function Tri(Request $request,VolsRepository $repository): Response
    {
        $critere=$request->get('special');



        $order=$request->get('type');

        if($order== "Croissant"){
            $vol = $repository->tri_asc();
        }

        else
        {
            $vol = $repository->tri_desc();
        }


        // Retrieve the entity manager of Doctrine



        // Render the twig view
        return $this->render('vols/index_tri.html.twig', [
            'vols' => $vol
        ]);
    }
}

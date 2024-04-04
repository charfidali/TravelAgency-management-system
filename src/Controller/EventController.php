<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Entity\Participation;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ParticipationRespository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

use MercurySeries\FlashyBundle\FlashyNotifier;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;






use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/Event")
 */
class EventController extends AbstractController
{
   
    /**
     * @Route("/event", name="app_event")
     */
    public function index(ParticipationRespository $a ,Request $request,PaginatorInterface $paginator): Response
    {
    /*    return $this->render('base.back.html.twig', [
            'controller_name' => 'EventController',
        ]);*/
        $rep=$this->getDoctrine()->getRepository(Evenement::class);
        
        $event=$rep->findAll();
        $event = $paginator->paginate(
            $event, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4/*limit per page*/
        );

        

       

    
       
        
    
        if($this->getUser()){
            $check=$a->selectbyevent($this->getUser()->getId());
            return $this->render('event/eventF.html.twig', [
                'event' =>$event,'check' => $check,
            ]);
        }else{

            return $this->render('event/eventF.html.twig', [
                'event' =>$event,

                
            ]);
        }

      

    }
     /**
     * @Route("/eventlist", name="lista")
     */
    public function liste(Request $request, PaginatorInterface $paginator ): Response
    {
        $rep=$this->getDoctrine()->getRepository(Evenement::class);
        $event=$rep->findAll();
        $event = $paginator->paginate(
            $event, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );


        $this->addFlash('success', 'Evenement ajouter avec succes!');

        $events=$this->getDoctrine()->getRepository(Evenement::class)->findAll();

        $pieChart = new PieChart();
        $data= array();
        $stat=['Les Types', '%'];
        array_push($data,$stat);

        $totalinf=0;
        $totalsup=0;
        foreach($events as $tmp)
        {
        
            if($tmp->getPrix() > 100)
            {
                $totalsup= $totalsup+1;
            }
            else{
                $totalinf= $totalinf+1;
            }
        }
        $stat=array();
        $stat=['Supperieur a 100',$totalsup];
        array_push($data,$stat);
        $stat=array();
        $stat=['inferieur a 100',$totalinf];
        array_push($data,$stat);


        $pieChart->getData()->setArrayToDataTable(
            $data
        );

        $pieChart->getOptions()->setTitle('STAT par rapport le prix ');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('event/eventList.html.twig', [
            'events' => $event,
            'piechart' => $pieChart

        ]);
    }   
    
    /**
 * @Route("/addevent", name="addevent")
*/
public function addevent(Request $request ): Response
{
    $evenement=new Evenement();
    $form=$this->createForm(EventType::class,$evenement);
    $form=$form->handleRequest($request);
    if($form->isSubmitted() )  {
        $evenement=$form->getData();
        $em=$this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();


        
        $this->addFlash('success', 'Evenement ajouter avec succes!');

        return $this->redirectToRoute('lista');


    }
    return $this->render('event/addevent.html.twig', [
        'f' => $form->createView(),

    ]);
}


/**
     * @Route("/imprimreservationm", name="imprimreservationm")
     */
    public function imprimEvent(): Response

    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        $event = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('event/imprimerEvent.html.twig', [
            'events' => $event,
                ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("listes des evenements.pdf", [
            "Attachment" => true
        ]);
    }






    /**
     * @Route("/update/{id}", name="updateevent")
     */

    public function update($id,Request $request): Response

    {
   
          $rep=$this->getDoctrine()->getRepository(Evenement::class);
        
           $event=$rep->find($id);
           $form=$this->createForm(EventType::class,$event);
           $form=$form->handleRequest($request);
           if($form->isSubmitted())
            {

            $em=$this->getDoctrine()->getManager();

           $em->flush();
            return $this->redirectToRoute('lista');
            
           }
       
              return $this->render('event/addevent.html.twig', [
                  'f' => $form->createView(), 'ev' => $event,
              ]);
   
   
    
    }     
    /**
 * @Route("/delete/{id}", name="delete_events")
 */
public function deleteEvent($id): Response
{
    $rep = $this->getDoctrine()->getRepository(Evenement::class);
    $em = $this->getDoctrine()->getManager();
    $evenement = $rep->find($id);
    $em->remove($evenement);
    $em->flush();

    return $this->redirectToRoute('lista');

    
}
    
     /**
 * @Route("/participer/{id}", name="participer")
*/
public function participer(MailerInterface $mailer,$id,Request $request,EventRepository $a): Response
{          
    
    
    $rep=$this->getDoctrine()->getRepository(Evenement::class);
         $event=$rep->find($id);
      $participation = new Participation();
      $participation->setIdevenement($id);
    
      $participation->setIduser($this->getUser()->getId());
      $em=$this->getDoctrine()->getManager();
      $em->persist($participation);
      $em->flush();
      $a->capaciteDOWNbyONE($id);
      
      $email = (new Email())
      ->from(Address::create('salut <mortadha.bouzgarrou@esprit.tn>'))                
      ->to($this->getUser()->getEmail())
      ->subject('Your Credentials')
      ->html('
      <center><h1>Hello '.$this->getUser()->getFirstname().',</h1></center>
      <p>hawka cbn Vous avez participer a event '.$event->getNom().' </p>

      
      ');
  $mailer->send($email);
    return $this->redirectToRoute('app_event');
    


  
}   
     /**
 * @Route("/imparticiper/{id}", name="imparticiper")
*/
public function imparticiper(MailerInterface $mailer,$id,ParticipationRespository $b,EventRepository $a): Response
{    $rep=$this->getDoctrine()->getRepository(Evenement::class);
     $event=$rep->find($id);
    
     $b->delete($id,$this->getUser()->getId());
      $a->capaciteUPbyONE($id);
      $email = (new Email())
      ->from(Address::create('salut <mortadha.bouzgarrou@esprit.tn>'))                
      ->to($this->getUser()->getEmail())
      ->subject('Your Credentials')
      ->html('
      <center><h1>Hello '.$this->getUser()->getFirstname().',</h1></center>
      <p>yedek ay Vous avez imparticiper a levent '.$event->getNom().' </p>

      
      ');
  $mailer->send($email);
    return $this->redirectToRoute('app_event');
}

















/**
     * @Route("/statevent", name="statevent")
     */
    public function stat()
    {

        $repository = $this->getDoctrine()->getRepository(Evenement::class);
        $event = $repository->findAll();

        $em = $this->getDoctrine()->getManager();


        $pr1 = 0;
        $pr2 = 0;


        foreach ($event as $event) {
            if ($event->getPrix() == "900")  :

                $pr1 += 1;
            else:

                $pr2 += 1;

            endif;

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['prix', 'nom'],
                ['900', $pr1],
                ['1200', $pr2],
            ]
        );
        $pieChart->getOptions()->setTitle('Prix des events');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('event/statEvent.html.twig', array('piechart' => $pieChart));
    }


    






    /**
         * @Route("/pdf/{id}", name="offre_pdf")
         */
        public function PDF(int $id)
    {
        //on definit les option du pdf
        $pdfOptions = new Options();
        //police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $event = $this->getDoctrine()->getRepository(Evenement::class)->find($id);



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('event/pdf.html.twig', [
            'event' => $event
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);



        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'paysage');

        // Render the HTML as PDF
        $dompdf->render();



        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Event.pdf", [
            "Attachment" => false
        ]);
        return new Response();
    }












/**
     * @Route("/displayEventMobile", name="displayEventMobile")
     * @return Response
     */
    public function displayEventMobile()
    {
        $rep=$this->getDoctrine()->getRepository(Evenement::class);
        $event=$rep->findAll();
      
        $normalizer = new ObjectNormalizer ();
        $normalizer -> setCircularReferenceHandler ( function ( $event ) {
            return $event -> getId ();
        });
        $serializer = new Serializer([ $normalizer ]);
        $formatted = $serializer->normalize($event , null , [ ObjectNormalizer::ATTRIBUTES => 
        ['id','nom','dateEvent','typeEvent','capacite','prix','image']]);
     
        return new JsonResponse(
            $formatted
     
        );



    
    }

    /**
     * @Route("/addEventMobile", name="addEventMobile")
     */
    public function addEventMobile(Request $request, NormalizerInterface $normalizer): Response
    {
        $event = new Evenement();
        $entityManager = $this->getDoctrine()->getManager();
        $event->setNom($request->get('nom'));

        $dateEvent = $request->query->get('dateEvent');
        $event->setDateEvent(new \DateTime($dateEvent));

        $typeEvent = $request->query->get('typeEvent');
        $event->setTypeEvent($typeEvent);

        
        $capacite = $request->query->get('capacite');
        $event->setCapacite($capacite);



        $prix = $request->query->get('prix');
        $event->setPrix($prix);


        $image = $request->query->get('image');
        $event->setImage($image);



        $em = $this->getDoctrine()->getManager();

        $em->persist($event);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("event ajouter");
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/updateEventMobile", name="updateEventMobile")
     */
    public function updateEventMobile(Request $request) {
        $rep = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $event = $rep->getRepository(Evenement::class)->find($id);
     
        $event->setNom($request->get('nom'));

        $dateEvent = $request->query->get('dateEvent');
        $event->setDateEvent(new \DateTime($dateEvent));

        $typeEvent = $request->query->get('typeEvent');
        $event->setTypeEvent($typeEvent);

        
        $capacite = $request->query->get('capacite');
        $event->setCapacite($capacite);



        $prix = $request->query->get('prix');
        $event->setPrix($prix);


        $image = $request->query->get('image');
        $event->setImage($image);



   

        $rep->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("event modifier");
        return new JsonResponse($formatted);

    }

    /**
 * @Route("/deletemobile/{id}", name="delete")
 */
public function deletemobile($id): Response
{
    $rep = $this->getDoctrine()->getRepository(Evenement::class);
    $em = $this->getDoctrine()->getManager();
    $evenement = $rep->find($id);
    $em->remove($evenement);
    $em->flush();
    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize("event deleted");
    return new JsonResponse($formatted);

    
}




}


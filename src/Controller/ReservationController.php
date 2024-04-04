<?php
namespace App\Controller;

use Endroid\QrCode\Builder\BuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ChambresRepository; 
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCodeBundle\Response\QrCodeResponse;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class ReservationController extends AbstractController
{
    public function __construct(BuilderInterface $customQrCodeBuilder)
{
    $result = $customQrCodeBuilder
        ->size(20)
        ->margin(10)
        ->build();

        

        $response = new QrCodeResponse($result);
}

    public function index()
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('default/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/reservation/pdf", name="pdf", methods={"GET","POST"})
     */
    public function offrepdf(ChambresRepository $chambresRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('chambres/myPdf.html.twig', [
            'c' => $chambresRepository->findAll(),                       
                'qrCode' => $qrCode,
                 ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
   
   
}





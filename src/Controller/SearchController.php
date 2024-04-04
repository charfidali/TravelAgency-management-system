<?php

namespace App\Controller;

use App\Entity\Vols;
use App\Form\SearchVolsType;
use App\Repository\VolsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class SearchController extends AbstractController
{

    /**
     * @Route("/vols/search", name="search_vols")
     */

     public function search(Request $request,VolsRepository $volsRepository)
     {
         $searchVolsForm = $this->createForm(SearchVolsType::class);
         $searchVolsForm->handleRequest($request);
         if ($searchVolsForm->isSubmitted() && $searchVolsForm->isValid()) {
             $criteria = $searchVolsForm->getData();
             // dd($criteria) ;
             $vols = $volsRepository->search($criteria);

             dd($vols);
         }

         return $this->render('search/vols.html.twig', [
             'search_form' => $searchVolsForm->createView(),
         ]);
     }
   /* public function search(Request $req, $str): Response
{
              $a= $req ->get('a');
              $em= $this->getDoctrine()->getManager() ->createQuery(
                  'SELECT h
                FROM App:vols h
                WHERE h.destination_aller LIKE :str')
                  ->setParameter('str', '%'.$str.'%')
                  ->getResult();


$vRep = $em->getRepository(Vols::class);
$volsQuery=$vRep->createQueryBuilder('v')
    ->orWhere('v.destination_aller: destination_aller')
    ->setParameter('destination_aller','%' .$a.'%')
    ->getQuery() ;
return $this->render('search/vols.html.twig',['volsQuery'=>$volsQuery,]) ;
} */




      /*  $form = $this->createForm(SearchVolsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // here we know that the form has been submitted.

            if ($form->isValid()) {
                // here we know that all submitted values ( form data ) are valid.
                $data = $form->getData();

                $vols = $volsRepository->searchVols($data);

                dd([
                    'data' => $data,
                    'result' => $vols,
                ]);
            } else {
                // hmm, form is submitted with incorrect values.

                $errors = $form->getErrors(true, true);

                dd($errors);
            }
        }

        return $this->render('search/vols.html.twig', [
            'search_form' => $form
        ]); */



}





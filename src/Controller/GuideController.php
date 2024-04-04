<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Form\GuideType;
use App\Repository\GuideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

    /**
     * @Route("/Guide")
     */
class GuideController extends AbstractController
{
    /**
     * @Route("/", name="app_guide_index", methods={"GET"})
     */
    public function index(GuideRepository $guideRepository): Response
    {
        return $this->render('guide/index.html.twig', [
            'guides' => $guideRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_guide_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GuideRepository $guideRepository): Response
    {
        $guide = new Guide();
        $form = $this->createForm(GuideType::class, $guide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guideRepository->add($guide);
            return $this->redirectToRoute('app_guide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guide/new.html.twig', [
            'guide' => $guide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_guide_show", methods={"GET"})
     */
    public function show(Guide $guide): Response
    {
        return $this->render('guide/show.html.twig', [
            'guide' => $guide,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_guide_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Guide $guide, GuideRepository $guideRepository): Response
    {
        $form = $this->createForm(GuideType::class, $guide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guideRepository->add($guide);
            return $this->redirectToRoute('app_guide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guide/edit.html.twig', [
            'guide' => $guide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_guide_delete", methods={"POST"})
     */
    public function delete(Request $request, Guide $guide, GuideRepository $guideRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guide->getId(), $request->request->get('_token'))) {
            $guideRepository->remove($guide);
        }

        return $this->redirectToRoute('app_guide_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/showfront", name="app_guide_indexfront", methods={"GET"})
     */
    public function indexfront(GuideRepository  $guideRepository): Response
    {
        return $this->render('guide/showfront.html.twig', [
            'guide' => $guideRepository->findAll(),
        ]);
    }



    /**
     * @Route("/", name="guides_index")
     */
    public function search(Request $request, PaginatorInterface $paginator): Response
    {

        $data=$request->get('data');
        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an Concerts entity
        $GuideRepository = $em->getRepository(guide::class);
        // Find all the data on the Concerts table, filter your query as you need
        $allVolsQuery = $GuideRepository->createQueryBuilder('guide')
            ->andWhere('guide.nom Like :nom ')

            ->setParameter('nom', '%'.$data.'%')

            ->getQuery();
        // Paginate the results of the query
        $guide = $paginator->paginate(
        // Doctrine Query, not results
            $allVolsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3);
        return $this->render('guide/showfront.html.twig', [
            'guide' => $guide,
        ]);
    }

    /**
     * @Route("/testri", name="testri")
     */
    public function Tri(Request $request,GuideRepository $repository): Response
    {
        $critere=$request->get('special');



        $order=$request->get('type');

        if($order== "Croissant"){
            $guide = $repository->tri_asc();
        }

        else
        {
            $guide = $repository->tri_desc();
        }


        // Retrieve the entity manager of Doctrine



        // Render the twig view
        return $this->render('guide/index_tri.html.twig', [
            'guide' => $guide
        ]);
    }

}

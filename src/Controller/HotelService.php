<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\HotelsRepository;
use App\Entity\Hotels;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HotelService extends AbstractController
{
  /**
 * @Route("/hotels/liste", name="liste", methods={"GET"})
 */
    public function liste(HotelsRepository $hotelsRepo)
    {
        
        $hotels = $hotelsRepo->findAll();

        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);

        // On convertit en json
        $jsonContent = $serializer->serialize($hotels, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;
    }
    /**
     * @Route("/hotels/ajout", name="ajout", methods={"GET"})
     */
    public function addHotels(Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        //if($request->isXmlHttpRequest()) {
            // On instancie un nouvel reclamation
            $hotels = new Hotels();

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());
           // dd($request->getContent());
            // On hydrate l'objet
            $hotels->setNom($request->get('nom'));
            $hotels->setNbetoiles($request->get('Nbetoiles'));
            $hotels->setAdresse($request->get('Adresse'));
            $hotels->setPointfort($request->get('Pointfort'));
            $hotels->setPointfort($request->get('image'));

            //$user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(["id" => 1]);


            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hotels);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', 201);
        //}
 
        //return new Response('Failed', 404);
    }
    
}
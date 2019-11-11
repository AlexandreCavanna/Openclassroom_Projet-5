<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    /**
     * @Route("/offers", name="offers_index")
     */
    public function index(OfferRepository $repo)
    {
        $offers = $repo->findAll();

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }
    
    /**
     * Permet de créer une offre
     *
     * @Route("/offers/new", name="offers_new")
     * @return Response
     */
    public function new(Request $request, ObjectManager $manager) {
        $offer = new Offer();
        
         $form = $this->createForm(OfferType::class, $offer);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {
             
            $offer->setAuthor($this->getUser());
            
            $manager->persist($offer);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'offre <strong> {$offer->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('offers_show', [
                'slug' => $offer->getSlug()
            ]);
         }

        return $this->render('offer/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Permet d'afficher le formulaire d'édition d'une offre
     * 
     * @Route("/offers/{slug}/edit", name="offers_edit")
     * 
     * @return Responce
     */
    public function edit(Offer $offer, Request $request, ObjectManager $manager) {
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
             
            $manager->persist($offer);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'offre <strong> {$offer->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('offers_show', [
                'slug' => $offer->getSlug()
            ]);
         }

        return $this->render('offer/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer
        ]);
    }

    /**
     * @Route("/offers/{slug}", name="offers_show")
     */
    public function show(Offer $offer) {
        
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

}

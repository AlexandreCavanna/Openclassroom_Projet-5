<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use App\Repository\CandidatureRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OfferController extends AbstractController
{
    /**
     * @Route("/offers/{page<\d+>?1}", name="offers_index")
     */
    public function index(OfferRepository $repo, $page)
    {
        $limit = 9;

        $start = $page * $limit - $limit;

        $total = count($repo->findAll());

        $pages = ceil($total / $limit);

        return $this->render('offer/index.html.twig', [
            'offers' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet de créer une offre
     *
     * @Route("/offers/new", name="offers_new")
     * @IsGranted("ROLE_EMPLOYER")
     * @return Response
     */
    public function new(Request $request, ObjectManager $manager, OfferRepository $repo)
    {
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid() && $availableSlug = 0 === $repo->count(['slug' => 'nonexistent'])) {

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
     * @Security("is_granted('ROLE_EMPLOYER') and user == offer.getAuthor()",
     *  message="Cette offre ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Responce
     */
    public function edit(Offer $offer, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
    public function show(Offer $offer, CandidatureRepository $repo)
    {
        $candidatures = $repo->findAll();
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'candidatures' => $candidatures
        ]);
    }

    /**
     * Permet de supprimer une offre
     *
     * @Route("offers/{slug}/delete", name="offers_delete")
     * @Security("is_granted('ROLE_USER') and user == offer.getAuthor()", message="Vous n'avez pas le droit d'accèder à cette ressource")
     * 
     * @param Offer $offer
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Offer $offer, ObjectManager $manager)
    {
        $manager->remove($offer);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'offre <strong>{$offer->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("account_index");
    }
}

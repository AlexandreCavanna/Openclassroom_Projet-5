<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\CandidatureRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{

    /**
     * @Route("/offers/{slug}/apply-job", name="candidatures_new")
     * @IsGranted("ROLE_STUDENT")
     */
    public function applyJob(Offer $offer, Request $request, ObjectManager $manager)
    {
        $candidature = new Candidature();

        $form = $this->createForm(CandidatureType::class, $candidature);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $cvFile = $form['cvFileName']->getData();

            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
 
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $cvFile->guessExtension();

                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
        
                }

                $candidature->setCvFilename($newFilename);
            }
            $user = $this->getUser();
            $candidature->setStudent($user)
                ->setoffer($offer);

            $manager->persist($candidature);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre candidature a bien été envoyée avec succès !"
            );

            return $this->redirectToRoute('home');
        }


        return $this->render('candidature/new.html.twig', [
            'offer' => $offer,
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route("/candidatures/{id}", name="candidatures_show")
     * @IsGranted("ROLE_EMPLOYER")
     * 
     */
    public function show(Candidature $candidature)
    {

        return $this->render('candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }
}

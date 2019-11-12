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
     * @Route("/candidatures", name="candidature_index")
     */
    public function index(CandidatureRepository $repo)
    {
        $offers = $repo->findAll();

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/offers/{slug}/apply-job", name="candidatures_new")
     * @IsGranted("ROLE_USER")
     */
    public function applyJob(Offer $offer, Request $request, ObjectManager $manager)
    {
        $candidature = new Candidature();

        $form = $this->createForm(CandidatureType::class, $candidature);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $cvFile = $form['cvFileName']->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $candidature->setCvFilename($newFilename);
            }
            $user = $this->getUser();
            $candidature->setStudent($user)
                    ->setoffer($offer);

            $manager->persist($candidature);
            $manager->flush();

            return $this->redirectToRoute('candidatures_show', ['id' => $candidature->getId(), 'withAlert' => true]);
            }
        

        return $this->render('candidature/new.html.twig', [
            'offer' => $offer,
            'form'  => $form->createView()
        ]);
     }

    /**
     * @Route("/candidatures/{id}", name="candidatures_show")
     * @IsGranted("ROLE_USER")
     */
     public function show(Candidature $candidature)
     {

        return $this->render('candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
     }


}

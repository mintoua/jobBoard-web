<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\OffreEmploiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OffreEmploiController extends AbstractController
{
    /**
     * @Route("/addjob", name="addjob")
     */
    public function addjob(Request $request)
    {
        $offre = new OffreEmploi();
        $form = $this->createForm(OffreEmploiType::class, $offre);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $offre->setIdRecruteur(null);
            $offre->setIdCandidat(null);
            $offre->setDateDebut(new \DateTime('now'));
            $em->persist($offre);
            $em->flush();
            return $this->render('offre_emploi/managejob.html.twig');
        }

        return $this->render('offre_emploi/postjob.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

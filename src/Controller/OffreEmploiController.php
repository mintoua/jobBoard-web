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
        $datenow = new \DateTime('now');
        $con = ($offre->getDateExpiration() > $datenow) && ($offre->getMinSalary() <= $offre->getMaxSalary());
        if ($con && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $offre->setIdRecruteur(null);
            $offre->setIdCandidat(null);
            $offre->setDateDebut(new \DateTime('now'));
            $em->persist($offre);
            $em->flush();
            $this->addFlash(
                'success',
                'Job added !'
            );
            return $this->redirectToRoute('joblist');
        } else if ($form->isSubmitted()) {
            return $this->render('offre_emploi/postjob.html.twig', [
                'form' => $form->createView(), 'message' => 'Check your fields !'
            ]);
        }

        return $this->render('offre_emploi/postjob.html.twig', [
            'form' => $form->createView(), 'message' => ''
        ]);
    }

    /**
     * @Route("/joblist", name="joblist")
     */
    public function readjob()
    {
        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $jobs = $r->findAll();
        return $this->render('offre_emploi/managejob.html.twig', [
            'list' => $jobs
        ]);
    }

    /**
     * @Route("/browsejob", name="browsejob")
     */
    public function browsejob()
    {
        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $jobs = $r->findAll();
        $nb = $r->countj();
        return $this->render('offre_emploi/browsejob.html.twig', [
            'list' => $jobs, 'nb' => $nb
        ]);
    }

    /**
     * @Route("/deljob/{id}", name="deljob")
     */
    public function deljob($id)
    {
        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository(OffreEmploi::class)->find($id);
        $em->remove($job);
        $em->flush();
        return $this->redirectToRoute('joblist');
    }

    /**
     * @Route("/modify/{id}", name="modify")
     */
    public function modjob(Request $request, $id)
    {
        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $job = $r->find($id);

        $form = $this->createForm(OffreEmploiType::class, $job);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('joblist');
        }

        return $this->render('offre_emploi/postjob.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

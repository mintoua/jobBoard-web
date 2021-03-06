<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\OffreEmploiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreEmploiRepository;

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
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $offre->setIdRecruteur(null);
                $offre->setIdCandidat(null);
                $offre->setDateDebut(new \DateTime('now'));
                $em->persist($offre);
                $em->flush();
                return $this->redirectToRoute('joblist');
            } else {
                return $this->render('offre_emploi/postjob.html.twig', [
                    'form' => $form->createView(), 'message' => 'Check your fields !'
                ]);
            }
        }
        return $this->render('offre_emploi/postjob.html.twig', [
            'form' => $form->createView(), 'message' => ''
        ]);
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
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($job);
                $em->flush();
                return $this->redirectToRoute('joblist');
            } else {
                return $this->render('offre_emploi/postjob.html.twig', [
                    'form' => $form->createView(), 'message' => 'Check your fields !'
                ]);
            }
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
        $jobs = $r->findBy([], ['date_debut' => 'DESC']);
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
        $jobs = $r->findBy([], ['date_debut' => 'DESC']);
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
     * @Route("/search", name="search")
     */
    public function searchjob(Request $request)
    {
        $title = $request->request->get('titre');
        $location = $request->request->get('location');
        $secteur = $request->request->get('secteur');

        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $jobs = $r->findBy(['titre' => $title, 'categorie' => $secteur, 'location' => $location]);
        $nb = $r->countsearch($title, $location, $secteur);

        return $this->render('offre_emploi/browsejob.html.twig', [
            'list' => $jobs, 'nb' => $nb
        ]);
    }
}

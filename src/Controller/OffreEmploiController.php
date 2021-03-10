<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\OffreEmploiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function readjob(Request $request, PaginatorInterface $pag)
    {
        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $donnes = $r->findBy([], ['date_debut' => 'DESC']);

        $jobs = $pag->paginate($donnes, $request->query->getInt('page', 1), 4);

        return $this->render('offre_emploi/managejob.html.twig', [
            'list' => $jobs
        ]);
    }

    /**
     * @Route("/browsejob", name="browsejob")
     */
    public function browsejob(Request $request, PaginatorInterface $pag)
    {
        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $filtre = $request->get("searchaj");

        $donnes = $r->getdonn($filtre);

        $jobs = $pag->paginate($donnes, $request->query->getInt('page', 1), 4);
        $nb = $r->countj($filtre);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('offre_emploi/content.html.twig', [
                    'list' => $jobs, 'nb' => $nb
                ])
            ]);
        }

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
    public function searchjob(Request $request, PaginatorInterface $pag)
    {
        $title = $request->request->get('titre');
        $location = $request->request->get('location');
        $secteur = $request->request->get('secteur');

        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $donnes = $r->search($title, $location, $secteur);
        $nb = $r->countsearch($title, $location, $secteur);
        $jobs = $pag->paginate($donnes, $request->query->getInt('page', 1), 4);

        return $this->render('offre_emploi/browsejob.html.twig', [
            'list' => $jobs, 'nb' => $nb
        ]);
    }


    /**
     * @Route("/pdf/{id}", name="pdf")
     */
    public function pofjob($id)
    {
        $job = $this->getDoctrine()->getManager()->getRepository(OffreEmploi::class)->findBy(['id' => $id]);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('offre_emploi/pdf.html.twig', [
            'list' => $job
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("PDFPffre.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('browsejob');
    }

    /**
     * @Route("/pdfAll", name="pdfAll")
     */
    public function pofjobs()
    {
        $job = $this->getDoctrine()->getManager()->getRepository(OffreEmploi::class)->findAll();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('offre_emploi/pdf.html.twig', [
            'list' => $job
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("PDFPffres.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('browsejob');
    }
}

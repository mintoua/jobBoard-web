<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Entity\Categorie;
use App\Entity\User;
use App\Entity\DemandeRecrutement;
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
     * @Route("/seeapp/{id}", name="seeapp")
     */
    public function seeapp($id, Request $request, PaginatorInterface $pag)
    {
        $r = $this->getDoctrine()->getRepository(User::class);
        $app = $this->getDoctrine()->getRepository(DemandeRecrutement::class)->findBy(['offre' => $id]);
        $off = $this->getDoctrine()->getRepository(OffreEmploi::class)->findBy(['id' => $id]);
        $arr = array();
        foreach ($app as $value) {
            $user = $r->findBy(['id' => $value->getCandidat()]);
            array_push($arr, array($app, $user));
        }
        //dump($arr[0][1]);

        $apps = $pag->paginate($app, $request->query->getInt('page', 1), 4);

        return $this->render('offre_emploi/manageapp.html.twig', [
            'list' => $apps, 'arr' => $arr, 'offre' => $off
        ]);
    }

    /**
     * 
     * @Route("/treatapp/{id}", name="treatapp")
     */
    public function treatapp($id, Request $request, PaginatorInterface $pag)
    {
        $this->getDoctrine()->getRepository(DemandeRecrutement::class)->treat($id);
        return $this->seeapp(3,  $request,  $pag);
    }

    /**
     * @Route("/modify/{id}", name="modify")
     */
    public function modjob(Request $request, $id)
    {
        $job = $this->getDoctrine()->getRepository(OffreEmploi::class)->find($id);
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
    public function pdfjobs()
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
    /**
     * @Route("/details", name="details")
     */
    public function details()
    {
        $r = $this->getDoctrine()->getManager();
        $categs = $r->getRepository(Categorie::class)->findAll();

        $categNom = [];
        $categColor = [];
        $categCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($categs as $categ) {
            $categNom[] = $categ->getNom();
            $categColor[] = $categ->getCouleur();
            $categCount[] = count($categ->getOffreemplois());
        }
        // On va chercher le nombre d'annonces publiées par date
        $annRepo = $r->getRepository(DemandeRecrutement::class);
        $annonces = $annRepo->countByDate();

        $dates = [];
        $annoncesCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($annonces as $annonce) {
            $dates[] = $annonce['dateAnnonces'];
            $annoncesCount[] = $annonce['count'];
        }

        return $this->render('offre_emploi/jobdetails.html.twig', [
            'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount),
            'dates' => json_encode($dates),
            'annoncesCount' => json_encode($annoncesCount),
        ]);
    }
}

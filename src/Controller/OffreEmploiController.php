<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\DemandeRecrutement;
use App\Form\OffreEmploiType;
use App\Repository\CategoryRepository;
use App\Repository\OffreEmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

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
        return $this->seeapp($this->getUser()->getId(),  $request,  $pag);
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

        $count = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($donnes as $job) {
            $count[] = count($job->getApplies());
        }

        $jobs = $pag->paginate($donnes, $request->query->getInt('page', 1), 4);

        return $this->render('offre_emploi/managejob.html.twig', [
            'list' => $jobs, 'count' => $count
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
        $categs = $r->getRepository(Category::class)->findAll();

        $categNom = [];
        $categColor = [];
        $categCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($categs as $categ) {
            $categNom[] = $categ->getTitre();
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


    /**
     * @Route("/listofferjson", name="listofferjson")
     */
    public function listofferjson(OffreEmploiRepository $repo)
    {
        $offers = $repo->findAll();
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        //relation //circular  referance
        //dump($offers);
        $data = $serializer->normalize($offers, null, array('attributes' => array(
            'id', 'titre', 'poste', 'description', 'date_debut',
            'date_expiration', 'maxSalary', 'minSalary', 'location', 'file', 'email', 'categorie' => ['id', 'titre'], 'applies' => ['id']
        )));
        //$data = $serializer->normalize($offers, 'json');

        return new JsonResponse($data);
    }

    /**
     * @Route("/listcategjson", name="listcategjson")
     */
    public function listcategjson(CategoryRepository $repo)
    {
        $categs = $repo->findAll();
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        //relation //circular  referance
        $data = $serializer->normalize($categs, null, array('attributes' => array(
            'id', 'titre'
        )));
        //$data = $serializer->normalize($offers, 'json');
        return new JsonResponse($data);
    }

    /**
     * @Route("/addofferjson", name="addofferjson")
     */
    public function addofferjson(Request $request, CategoryRepository $categ)
    {
        $offer = new OffreEmploi();
        $offer->setTitre($request->query->get('titre'));
        $offer->setPoste($request->query->get('poste'));
        $offer->setIdRecruteur(null);
        $offer->setIdCandidat(null);
        $offer->setDescription($request->query->get('description'));
        $offer->setDateDebut(new \DateTime('now'));
        $offer->setDateExpiration(new \DateTime($request->query->get('date_expiration')));
        $offer->setMaxSalary($request->query->get('maxSalary'));
        $offer->setMinSalary($request->query->get('minSalary'));
        $offer->setLocation($request->query->get('location'));
        $offer->setCategorie($categ->find($request->query->get('categ')));
        $offer->setFile($request->query->get('file'));
        $offer->setEmail($request->query->get('email'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($offer);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($offer, null, array('attributes' => array(
            'id', 'titre', 'poste', 'description', 'date_debut',
            'date_expiration', 'maxSalary', 'minSalary', 'location', 'file', 'email', 'categorie' => ['id', 'titre'], 'applies' => ['id']
        )));
        return new JsonResponse($data);
    }

    /**
     * @Route("/updateofferjson", name="updateofferjson")
     */
    public function updateofferjson(Request $request, OffreEmploiRepository $ser, CategoryRepository $categ)
    {
        $offer = $ser->find($request->query->get('id'));
        $offer->setTitre($request->query->get('titre'));
        $offer->setPoste($request->query->get('poste'));
        $offer->setIdRecruteur(null);
        $offer->setIdCandidat(null);
        $offer->setDescription($request->query->get('description'));
        $offer->setDateExpiration(new \DateTime($request->query->get('date_expiration')));
        $offer->setMaxSalary($request->query->get('maxSalary'));
        $offer->setMinSalary($request->query->get('minSalary'));
        $offer->setLocation($request->query->get('location'));
        $offer->setCategorie($categ->find($request->query->get('categ')));
        $offer->setFile($request->query->get('file'));
        $offer->setEmail($request->query->get('email'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($offer);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($offer, null, array('attributes' => array(
            'id', 'titre', 'poste', 'description', 'date_debut',
            'date_expiration', 'maxSalary', 'minSalary', 'location', 'file', 'email', 'categorie' => ['id', 'titre'], 'applies' => ['id']
        )));
        return new Response("updated");
    }

    /**
     * @Route("/deleteofferjson", name="deleteofferjson")
     */
    public function deleteofferjson(Request $req, EntityManagerInterface $em)
    {
        $job = $this->getDoctrine()->getRepository(OffreEmploi::class)->find($req->query->get('id'));
        $em->remove($job);
        $em->flush();
        return new Response("deleted");
    }

    /**
     * 
     * @Route("/treatappjson", name="treatappjson")
     */
    public function treatappjson(Request $request, SerializerInterface $ser)
    {
        $content = $request->getContent();
        $var = json_decode($content);
        $this->getDoctrine()->getRepository(DemandeRecrutement::class)->treat($var->{'id'},);
        return new Response("treated");
    }

    /**
     * @Route("/seeappjson", name="seeappjson")
     */
    public function seeseeappjsonapp(Request $request, SerializerInterface $ser)
    {
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        $content = $request->getContent();
        $var = json_decode($content);
        $app = $this->getDoctrine()->getRepository(DemandeRecrutement::class)->findBy(['offre' => $var->{'id'}]);
        $data = $serializer->normalize($app, null, array('attributes' => array(
            'id', 'offre' => ['id', 'titre'], 'candidat' => ['id', 'firstName', 'lastName'], 'status', 'date_debut', 'date_expiration'
        )));
        return new JsonResponse($data);
    }

    /**
     * @Route("/data", name="data")
     */
    public function data(OffreEmploiRepository $repo)
    {
        $data = $repo->countByDate();
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        $data = $serializer->normalize($data, 'json');
        return new JsonResponse($data);
    }
}

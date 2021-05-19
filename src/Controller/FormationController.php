<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategorySearch;
use App\Entity\Formation;
use App\Form\CategoryType;
use App\Form\CategorySearchType;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Form\PropertySearchType;
use App\Entity\PropertySearch;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FormationController extends AbstractController
{


    /**
     * @Route("/formation", name="formation")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class, $categorySearch);
        $form->handleRequest($request);

        $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([], ['id' => 'desc']);

        $formation = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();

            if ($category != "")
                $donnees = $category->getFormation();
            else
                $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([], ['id' => 'desc']);
            $formation = $paginator->paginate(
                $donnees, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                4 // Nombre de résultats par page
            );
        }

        return $this->render('formation/formation.html.twig', ['form' => $form->createView(), 'formation' => $formation]);
    }


    /**
     * @Route("/formationfree", name="formation_free")
     */
    public function indexxx(Request $request, PaginatorInterface $paginator)
    {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class, $categorySearch);
        $form->handleRequest($request);

        $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([], ['id' => 'desc']);
        $formation = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();

            if ($category != "")
                $donnees = $category->getFormation();
            else

                $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([], ['id' => 'desc']);
            $formation = $paginator->paginate(
                $donnees, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                4 // Nombre de résultats par page
            );
        }

        return $this->render('formation/formationfree.html.twig', ['form' => $form->createView(), 'formation' => $formation]);


    }


    /**
     * @Route("/formation/new", name="new_formation")
     * Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        $formation = new formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('formation');
        }
        return $this->render('formation/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/pdf", name="formation_pdf")
     */
    public function pdfformation()
    {


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $formation = $this->getDoctrine()->getManager()->getRepository(Formation::class)->findAll();
        $html = $this->renderView('formation/pdf.html.twig', [
            'list' => $formation,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("liste-formation.pdf", [
            "Attachment" => true
        ]);
        return new Response ($dompdf->stream());


    }


    /**
     * @Route("/pdfree", name="formation_pdfree")
     */
    public function pdfformationfree()
    {


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $formation = $this->getDoctrine()->getManager()->getRepository(Formation::class)->findAll();
        $html = $this->renderView('formation/pdfree.html.twig', [
            'list' => $formation,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("formation-gratuites.pdf", [
            "Attachment" => true
        ]);
        return new Response ($dompdf->stream());


    }


    /**
     * @Route("/formation/edit/{id}", name="edit_formation")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $formation = new formation;
        $formation = $this->getDoctrine()->getRepository(formation::class)->find($id);

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('formation');
        }

        return $this->render('formation/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/formation/editcat/{id}", name="edit_category")
     * Method({"GET", "POST"})
     */
    public function editc(Request $request, $id)
    {
        $category = new category;
        $category = $this->getDoctrine()->getRepository(category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('formation/editcategory.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/formation/delete/{id}",name="delete_formation")
     * Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $formation = $this->getDoctrine()->getRepository(formation::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($formation);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('formation');

    }


    /**
     * @Route("/formation/deletecategory/{id}",name="delete_category")
     * Method({"DELETE"})
     */
    public function deletec(Request $request, $id)
    {

        $category = $this->getDoctrine()->getRepository(category::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);

        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirectToRoute('category');

    }

    /**
     * @Route("/formation/{id}", name="formation_show")
     */
    public function show($id)
    {
        $formation = $this->getDoctrine()->getRepository(formation::class)->find($id);
        return $this->render('formation/show.html.twig',
            array('formation' => $formation));
    }

    /**
     * @Route("/category", name="category")
     */
    public function showc()
    {
        $category = $this->getDoctrine()->getRepository(category::class)->findAll();
        return $this->render('formation/listecategory.html.twig',
            array('category' => $category));

    }


    /**
     * @Route("/category/newCat", name="add_Categorie")
     * Method({"GET", "POST"})
     */
    public function newCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category');
        }
        return $this->render('formation/newCategory.html.twig', ['form' =>
            $form->createView()]);
    }


    /**
     * @Route("/planification", name="planification")
     */
    public function indexplan(FormationRepository $calendar)
    {
        $events = $calendar->findAll();


        Source: https://prograide.com/pregunta/59280/afficher-plus-de-texte-en-plein-calendrier
        $rdvs = [];

        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDateDebut()->format('Y-m-d'),
                'end' => $event->getDateFin()->format('Y-m-d'),
                'title' => $event->getNom(),
                'description' => $event->getDescription(),


            ];
        }


        $data = json_encode($rdvs);

        return $this->render('formation/plan.html.twig', compact('data'));
    }


    /**
     * @Route("/recherche",name="recherche")
     */
    public function home(Request $request)
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);
        //initialement le tableau des articles est vide,
        //c.a.d on affiche les articles que lorsque l'utilisateur
        //clique sur le bouton rechercher
        $formation = [];

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom d'article tapé dans le formulaire

            $nom = $propertySearch->getNom();
            if ($nom != "")

                $formation = $this->getDoctrine()->getRepository(Formation::class)->findBy(['nom' => $nom]);
            else
                //si si aucun nom n'est fourni on affiche tous les articles
                $formation = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        }
        return $this->render('formation/recherche.html.twig', ['form' => $form->createView(), 'formation' => $formation]);
    }

    /**
     * @Route("/getallcategories", name="category*")
     * methods={"GET"}
     */

    public function getAllCat(NormalizerInterface $normalizer)
    {

        $category = $this->getDoctrine()->getRepository(category::class)->findAll();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $formatted = $serializer->normalize($category, null, array('attributes' => array(
            'id', 'titre', 'descriptionc'
        )));
        return new JsonResponse($formatted, 200);
    }

    /**
     * @Route("/deletecatjson", name="deletecatjson")
     */
    public function deletecatjson(Request $req, EntityManagerInterface $em)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($req->get('id'));
        $em->remove($category);
        $em->flush();
        return new Response('deleted');
    }

    /**
     * @Route("/deletjsonefor",name="delete_formation*")
     */
    public function deleteforjson(Request $req, EntityManagerInterface $em)
    {
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($req->get('id'));
        $em->remove($formation);
        $em->flush();
        return new Response('deleted');
    }


    /**
     * @Route("/formationjson", name="formation*")
     * Method({"GET"})
     */


    public function getAllfor(NormalizerInterface $normalizer)
    {
        $formation = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $formatted = $serializer->normalize($formation, null, array('attributes' => array(
            'id', 'category_id', 'nom', 'formateur', 'description', 'date_debut', 'date_fin', 'adresse', 'mail',
            'tel', 'prix'
        )));
        return new JsonResponse($formatted, 200);


    }


    /**
     * @Route("/categorynewCat", name="new_categoryjson")
     * Method({"GET"})
     */
    public function newCategoryjson(Request $request, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $category = new category();
        $category->setTitre($request->get('titre'));
        $category->setDescriptionc($request->get('descriptionc'));
        $em->persist($category);
        $em->flush();
        $jsonContent = $Normalizer->normalize($category, 'json');
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/editcatjson/{id}", name="edit_category")
     * Method({"GET", "POST"})
     */
    public function editcat(Request $request, NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(category::class)->find($id);
        $category->setTitre($request->get('titre'));
        $category->setDescriptionc($request->get('descriptionc'));
        $em->flush();
        $jsonContent = $Normalizer->normalize($category, 'json', ['groups' => 'post:read']);
        return new Response("updated successfully" . json_encode($jsonContent));
    }


    /**
     * @Route("/formationNew", name="new_formation*")
     */
    public function newformationjson(Request $request, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $formation = new formation();
        $formation->setNom($request->get('nom'));
        $formation->setFormateur($request->get('formateur'));
        $formation->setDescription($request->get('description'));
        $formation->setDateDebut(new \DateTime($request->query->get('date_debut')));
        $formation->setDateFin(new \DateTime($request->query->get('date_fin')));
        // $formation->setCategorie($categ->find($request->query->get('categ')));
        //$formation->setDateDebut($request->get('date_debut'));
        // $formation->setDateFin($request->get('date_fin'));
        $formation->setAdresse($request->get('adresse'));
        $formation->setmail($request->get('mail'));
        $formation->settel($request->get('tel'));
        $formation->setPrix($request->get('prix'));
        $em->persist($formation);
        $em->flush();
        //    $jsonContent = $Normalizer->normalize($formation, 'json', ['groups' => 'post:read']);
        return new Response("Added");

    }


}


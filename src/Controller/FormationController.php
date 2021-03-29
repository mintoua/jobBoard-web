<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategorySearch;
use App\Entity\Formation;
use App\Form\CategoryType;
use App\Form\CategorySearchType;
use App\Form\FormationType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class FormationController extends AbstractController
{


    /**
     * @Route("/formation", name="formation")
     */
    public function index(Request $request,PaginatorInterface $paginator){
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class,$categorySearch);
        $form->handleRequest($request);

        $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([],['id' => 'desc']);

        $formation = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        if($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();

            if ($category!="")
                $donnees= $category->getFormation();
            else
                $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([],['id' => 'desc']);
                $formation = $paginator->paginate(
                    $donnees, // Requête contenant les données à paginer (ici nos articles)
                    $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                    4 // Nombre de résultats par page
                );
        }

        return $this->render('formation/formation.html.twig',['form' => $form->createView(),'formation' => $formation]);
 }







    /**
     * @Route("/formationfree", name="formation_free")
     */
    public function indexxx(Request $request, PaginatorInterface $paginator){
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class,$categorySearch);
        $form->handleRequest($request);

        $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([],['id' => 'desc']);
        $formation= $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        if($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();

            if ($category!="")
                $donnees= $category->getFormation();
            else

                $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([],['id' => 'desc']);
            $formation= $paginator->paginate(
                $donnees, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                4 // Nombre de résultats par page
            );
        }

        return $this->render('formation/formationfree.html.twig',[ 'form' =>$form->createView(), 'formation' => $formation]);


    }



    /**
     * @Route("/formation/new", name="new_formation")
     * Method({"GET", "POST"})
     */
    public function new(Request $request) {
        $formation = new formation();
        $form = $this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('formation');
        }
        return $this->render('formation/new.html.twig',['form' => $form->createView()]);
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
    public function edit(Request $request, $id) {
        $formation = new formation;
        $formation = $this->getDoctrine()->getRepository(formation::class)->find($id);

        $form = $this->createForm(FormationType::class,$formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('formation');
        }

        return $this->render('formation/edit.html.twig', ['form' =>$form->createView()]);
    }
    /**
     * @Route("/formation/editcat/{id}", name="edit_category")
     * Method({"GET", "POST"})
     */
    public function editc(Request $request, $id) {
        $category = new category;
        $category = $this->getDoctrine()->getRepository(category::class)->find($id);

        $form = $this->createForm(CategoryType::class,$category );

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('formation/editcategory.html.twig', ['form' =>$form->createView()]);
    }




    /**
     * @Route("/formation/delete/{id}",name="delete_formation")
     * Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
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
    public function deletec(Request $request, $id) {

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
    public function show($id) {
        $formation= $this->getDoctrine()->getRepository(formation::class)->find($id);
        return $this->render('formation/show.html.twig',
            array('formation' => $formation));
    }
    /**
     * @Route("/category", name="category")
     */
    public function showc() {
        $category= $this->getDoctrine()->getRepository(category::class)->findAll();
        return $this->render('formation/listecategory.html.twig',
            array('category' => $category));

    }


    /**
     * @Route("/category/newCat", name="new_category")
     * Method({"GET", "POST"})
     */
    public function newCategory(Request $request) {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category');
        }
        return $this->render('formation/newCategory.html.twig',['form'=>
            $form->createView()]);
    }



}

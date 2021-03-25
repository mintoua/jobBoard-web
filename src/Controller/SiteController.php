<?php
/**
 * Created by PhpStorm.
 * User: Ryaan
 * Date: 17/01/18
 * Time: 11:49
 */

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Categorie;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    function homepage()
    {
        $categ = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();
        return $this->render('index.html.twig',['categ'=>$categ]);
    }

    /**
     * @Route("/admin/homepage", name="adminHomePage")
     * @return Response
     */
    function adminpage()
    {
        return $this->render('index.html.twig');
    }
    /**
     * @Route("/entreprise/homepage", name="entrepriseHomePage")
     * @return Response
     */
    function candidatepage()
    {
        return $this->render('index.html.twig');
    }
}

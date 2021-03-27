<?php

namespace App\Controller;

use App\Form\CompanyprofileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



    /**
     * @Route("/company", name="company_")
     */
class CompanyController extends AbstractController
{
    /**
     * @Route("/profile", name="companyprofile")
     */

    public function companyprofile(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        else{
            $em= $this->getDoctrine()->getManager();
            $company=$this->getUser();
            $form=$this->createForm(CompanyprofileType::class, $company);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $company->setRoles(array("ROLE_COMPANY"));
                $em->persist($company);
                $em->flush();
            }
    }
        return $this->render('company/companyprofile.html.twig', [
        'form'=>$form->createView()
    ]);
    }



}

<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyprofileType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



    /**
     * @Route("/company", name="company_")
     */
class CompanyController extends AbstractController
{
    /**
     * @Route("/profile", name="companyprofile")
     */

    public function AddEditCompany(Request $request, FileUploader $fileUploader)
    {
        $em= $this->getDoctrine()->getManager();
        $loggedUser = $this->getUser();

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $company = $em->getRepository(Company::class)->findOneByUserId($loggedUser);

        if ($company and !$company->getStatus()){
            return $this->render('company/pending-company-request.html.twig');
        }
        else{
            !$company && $company = new Company();
            $form=$this->createForm(CompanyprofileType::class, $company);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                /**
                 * @var UploadedFile $companyimage
                 */
                $companyimage = $form->get('companyImageName')->getData();
                if ($companyimage) {
                    $newFilename = $fileUploader->upload($companyimage);
                    $company->setCompanyImageName($newFilename);
                }
                $company->setStatus(false);
                !$company->getUserId() && $company->setUserId($loggedUser);
                $company->setStatus(0);
                $em->persist($company);
                $em->flush();

            }
    }
        return $this->render('company/companyprofile.html.twig', [
        'form'=>$form->createView(),
            'company' => $company

    ]);
    }
    /**
     * @Route("/companies", name="companies_list")
     */

    public function ShowCompanies(Request $request, PaginatorInterface $paginator)
    {
        $company = $this->getDoctrine()->getRepository(Company::class)->findAll();
        $pagination = $paginator->paginate($company, $request->query->getInt('page', 1), 5);
        return $this->render('company/companiesList.html.twig', [
            'companies_list' => $pagination,
        ]);
    }



}

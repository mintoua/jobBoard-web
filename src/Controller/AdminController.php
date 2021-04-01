<?php

namespace App\Controller;

use App\Entity\CandidateResume;
use App\Entity\Company;
use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/users", name="users_list")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function showUsers(Request $request, PaginatorInterface $paginator)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $pagination = $paginator->paginate(
            $users, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('admin/users.html.twig', [
            'users_list' => $pagination,
        ]);
    }

    /**
     * @Route("/companies", name="companies_list")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function ShowCompanies(Request $request, PaginatorInterface $paginator)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}

        $companies = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findAll();
        $pagination = $paginator->paginate(
            $companies, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('admin/companies.html.twig', [
            'companies_list' => $pagination,
        ]);
    }


    /**
     * @Route("/resumes", name="resumes_list")
     */

    public function ShowCandidatesResume(Request $request, PaginatorInterface $paginator)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $resumes = $this->getDoctrine()->getRepository(CandidateResume::class)->findAll();
        $pagination = $paginator->paginate($resumes, $request->query->getInt('page', 1), 4);
        return $this->render('admin/candidatesresume.html.twig', [
            'candidates_resumes' => $pagination,
        ]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/deleteresume/{id}", name="delete_resume")
     */


    public function deleteResume($id)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $em = $this->getDoctrine()->getManager();
        $resume = $em->getRepository(CandidateResume::class)->find($id);
        $em->remove($resume);
        $em->flush();
        return $this->redirectToRoute('admin_resumes_list');

    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/deletecompany/{id}", name="delete_company")
     */


    public function deleteCompany($id)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository(Company::class)->find($id);
        $em->remove($company);
        $em->flush();
        return $this->redirectToRoute('admin_companies_list');

    }


    /**
     * @Route("/users/{role}/", name="users_filter")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function TribyRole(Request $request, PaginatorInterface $paginator, $role)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')->from('App:User', 'u')->where('u.roles LIKE :roles')->setParameter('roles', '%"' . $role . '"%');
        $users = $qb->getQuery()->getResult();
        $pagination = $paginator->paginate(
            $users, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('admin/users.html.twig', [
            'users_list' => $pagination,
        ]);
    }

    /**
     * @Route("/users/changeStatus/{id}", name="users_isActive")
     */
    public function userChangeIsActive(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $this->getDoctrine()->getRepository(User::class)->find($id);
        $users->getIsActive() ? $users->setIsActive(0) : $users->setIsActive(1);
        $em->persist($users);
        $em->flush();
        $this->addFlash('success', 'User Status changed successfully');
        return $this->redirect($this->generateUrl('admin_users_list'));
    }

    /**
     * @Route("/companies/changeStatus/{id}", name="companies_status")
     */
    public function companyStatus(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $this->getDoctrine()->getRepository(Company::class)->find($id);
        $companies->getStatus() ? $companies->setStatus(0) : $companies->setStatus(1);
        $companyowner= $companies->getUserId();
        $companyowner->setRoles(array("ROLE_COMPANY"));
        $em->persist($companyowner);
        $em->persist($companies);
        $em->flush();
        $this->addFlash('success', 'Company status changed successfully');
        return $this->redirect($this->generateUrl('admin_companies_list'));
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function showAction() {

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('admin/users.html.twig', [
            'users_list' => $users,
        ]);
    }
    /**
     * @Route("/users/changeStatus/{id}", name="users_isActive")
     */
    public function userChangeIsActiveAction(int $id) {

        $em = $this->getDoctrine()->getManager();
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        $users->getIsActive() ? $users->setIsActive(0):$users->setIsActive(1);
        $em->persist($users);
        $em->flush();
        $this->addFlash('success', 'User Status changed successfully');
        return $this->redirect($this->generateUrl('admin_users_list'));
    }
}

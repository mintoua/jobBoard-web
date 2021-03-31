<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }


    /**
     * @Route("/addComment/{id}", name="add-comment")
     */
        function ajouterCommentaire(Request $req, $id)
        {
            $em = $this->getDoctrine()->getManager();
            $listEventTri=$this->getDoctrine()->getRepository(Event::class)->findAllOrderByDate();
            $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
            $listCommentaire = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('idEvent' => $event));
            $nbreC = count($listCommentaire);
            $commentaire = new Comment();
            if ($req->isMethod('post')) {
                $today = new \DateTime();
                $commentaire->setCreatedAt($today);
                $commentaire->setEmail($req->get('email'));
                $commentaire->setIdEvent($event);
                $commentaire->setMessage($req->get('message'));
                $commentaire->setName($req->get('name'));
                $commentaire->setPhone($req->get('phone'));
                try {
                    $em->persist($commentaire);
                    $em->flush();
                    return $this->redirectToRoute('add-comment', array('id' => $id));
                } catch (Exception $e) {
                }
            }
            return $this->render('event/DetailEvent.html.twig', array('singleEvent' => $event, 'comments' => $listCommentaire, 'nbr' => $nbreC,'listTri'=> $listEventTri));
        }
    }


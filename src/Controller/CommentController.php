<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $listEventTri = $this->getDoctrine()->getRepository(Event::class)->findAllOrderByDate();
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
        return $this->render('event/DetailEvent.html.twig', array('singleEvent' => $event, 'comments' => $listCommentaire, 'nbr' => $nbreC, 'listTri' => $listEventTri));
    }
    /**
     * @Route("/addCommentjson", name="addCommentjson")
     */
    function addCommentjson(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $this->getDoctrine()->getRepository(Event::class)->find($req->query->get('id'));
        $commentaire = new Comment();
        $today = new \DateTime();
        $commentaire->setCreatedAt($today);
        $commentaire->setEmail($req->query->get('email'));
        $commentaire->setIdEvent($event);
        $commentaire->setMessage($req->query->get('message'));
        $commentaire->setName($req->query->get('name'));
        $commentaire->setPhone($req->query->get('phone'));
        $em->persist($commentaire);
        $em->flush();
        $listCommentaire = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('idEvent' => $event));
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($listCommentaire, null, array('attributes' => array(
            'id', 'email', 'message', 'name', 'phone', 'event' => ['id']
        )));
        return new JsonResponse($data);
    }


    /**
     * @Route("/listcomms", name="listcomms")
     */
    function listcomms(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $this->getDoctrine()->getRepository(Event::class)->find($req->query->get('id'));
        $listCommentaire = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('idEvent' => $event));
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $data = $serializer->normalize($listCommentaire, null, array('attributes' => array(
            'id', 'email', 'message', 'name', 'createdAt', 'event' => ['id']
        )));
        return new JsonResponse($data);
    }
}

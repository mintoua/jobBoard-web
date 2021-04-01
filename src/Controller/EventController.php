<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
    /**
     * @Route("/ajouter", name="ajout_event")
     */
    public function ajouterEvent(Request $req){
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        $em= $this->getDoctrine()->getManager();
        $event=new Event();
        $form=$this->createForm(EventType::class,$event);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $file=$event->getImage();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $em=$this->getDoctrine()->getManager();
            $event->setImage($fileName);
            try{
                $file->move(
                    $this->getParameter('EventImage_directory'),
                    $fileName
                );
            }
            catch(FileException $e){}
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('ListEvent');
        }

        return $this->render('event/ajouterEvent.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/afficher", name="ListEvent")
     */
    public function displayAllEvent(Request  $req,PaginatorInterface $paginator){
        // Paginate the results of the query

        $AlllistEvent=$this->getDoctrine()->getRepository(Event::class)->findAll();
        $nbr=count($AlllistEvent);
        if ($req->isMethod('post')){
          $criteria=$req->get('search-input');
            $AlllistEvent=$this->getDoctrine()->getRepository(Event::class)->findMultiple($criteria);
          $nbr=count($AlllistEvent);
            $listEvent= $paginator->paginate(
            // Doctrine Query, not results
                $AlllistEvent,
                // Define the page parameter
                $req->query->getInt('page', 1),
                // Items per page
                3
            );
            return $this->render('event/listEvent.html.twig', [
                'listEvent' => $listEvent, 'nbr'=>$nbr
            ]);

        }
        $listEvent= $paginator->paginate(
        // Doctrine Query, not results
            $AlllistEvent,
            // Define the page parameter
            $req->query->getInt('page', 1),
            // Items per page
            3
        );
        return $this->render('event/listEvent.html.twig', [
            'listEvent' => $listEvent, 'nbr'=>$nbr
        ]);

    }

    /**
     * @Route("/trierpar", name="TriPar")
     */
    public function TriPar(Request  $req,PaginatorInterface $paginator){

        $AlllistEvent=$this->getDoctrine()->getRepository(Event::class)->findAll();
        $nbr=count($AlllistEvent);
        if ($req->isMethod('post')){

            if( $req->get('') =="prix")
            { $AlllistEvent=$this->getDoctrine()->getRepository(Event::class)->orderByPrice();}
            else if ($req->get('search-tri')=="nbrePlace"){
            $AlllistEvent=$this->getDoctrine()->getRepository(Event::class)->orderByTicket();
        }
            else if ($req->get('search-tri')=="date") {
            $AlllistEvent=$this->getDoctrine()->getRepository(Event::class)->findAllOrderByDate();
        }
            $nbr=count($AlllistEvent);
            $listEvent= $paginator->paginate(
            // Doctrine Query, not results
                $AlllistEvent,
                // Define the page parameter
                $req->query->getInt('page', 1),
                // Items per page
                3
            );
            return $this->render('event/listEvent.html.twig', [
                'listEvent' => $listEvent, 'nbr'=>$nbr
            ]);

        }
        $listEvent= $paginator->paginate(
        // Doctrine Query, not results
            $AlllistEvent,
            // Define the page parameter
            $req->query->getInt('page', 1),
            // Items per page
            3
        );
        return $this->render('event/listEvent.html.twig', [
            'listEvent' => $listEvent, 'nbr'=>$nbr
        ]);

    }

    /**
     * @Route("/afficher/{id}", name="afficherDetailComment")
     */
    public function afficherDetail($id)
    {
        $AllEventTri=$this->getDoctrine()->getRepository(Event::class)->findAllOrderByDate();
        $singleEvent = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $listCommentaire = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('idEvent' => $singleEvent));
        $nbreC = count($listCommentaire);
        return $this->render('event/DetailEvent.html.twig', [
            'singleEvent' => $singleEvent, 'id' => $id,'comments' => $listCommentaire, 'nbr' => $nbreC, 'listTri'=>$AllEventTri
        ]);
    }
    /**
     * @Route("/supprimer/{id}", name="supprimer_event")
     */
    public function supprimerEvent($id)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('security_login'));
        }
        $em= $this->getDoctrine()->getManager();
        $evenement=$em->getRepository( Event::class)->find($id);
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute( "ListEvent");
    }
    /**
     * @Route("/modifier/{id}", name="modifier_event")
     */
    public function modifierEvent(Request $req,$id){
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('security_login'));
        }
        $evenement = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $form = $this->createFormBuilder ($evenement)
            ->add('nom', TextType::class,[
                'attr'=> [
                    'placeholder' =>'Nom event',
                    'class'=> 'form-control'
                ]
            ])
            ->add('description', TextType::class,[
                'attr'=>[
                    'placeholder' => 'Description',
                    'class' => 'form-control',
                    'required'=> false
                ]
            ])
            ->add('adresse', TextType::class,[
                'attr'=> [
                    'placeholder' =>'adresse',
                    'class'=> 'form-control'
                ]
            ])
            ->add('prix', TextType::class,[
                'attr'=> [
                    'placeholder' => 'Prix',
                    'class' =>'form-control',
                    'type' => 'number',
                    'step'=>'any',
                    'empty_data'=> '0'
                ]
            ])
            ->add('nbrePlace', TextType::class,[
                'attr'=>[
                    'placeholder' => 'capacité',
                    'class' =>'form-control',
                    'type' => 'number',
                    'empty_data'=> '0'
                ]
            ])
            ->add('date', DateType::class,[
                'widget' => 'single_text',
                'attr' =>[
                    'class' => 'form-control',
                    'placeholder'=> 'dd/mm/yyyy',
                    'type'=>'date'
                ]
            ])
            ->getForm();
        $form ->handleRequest($req);
        if ($form->isSubmitted()) {
            $entity = $this->getDoctrine()->getManager();
            $entity->merge($evenement);
            $entity->flush();
            return $this->redirectToRoute('ListEvent');
        }
        return $this->render('event/modifierEvent.html.twig' , [ 'form' => $form->createView()]);
    }






}

<?php

namespace App\Controller;

use App\Entity\DemandeRecrutement;
use App\Entity\OffreEmploi;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DemandeRecrutementController extends AbstractController
{
    /**
     * @Route("/apply/{id}", name="apply")
     */
    public function addapp($id, Request $request, PaginatorInterface $pag, \Swift_Mailer $mailer)
    {
        $apply = new DemandeRecrutement();

        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $job = $r->find($id);
        $a = $this->getDoctrine()->getRepository(User::class);
        $user = $a->find($this->getUser()->getId());
        $b = $this->getDoctrine()->getRepository(DemandeRecrutement::class);

        $apply->setOffre($job);
        $apply->setStatus(false);
        $apply->setCandidat($user);
        $apply->setDateDebut(new \DateTime('now'));
        $exp = new \DateTime('now + 10 day');
        //$exp->format('Y-m-d H:i:s');
        //date('Y-m-d H:i:s', strtotime('+1 day', $exp));
        $apply->setDateexpiration($exp);
        if ($b->finddemande($id, $user->getId()) == null) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apply);
            $em->flush();
            $mes = "Job Applied";
            $message = (new \Swift_Message('Nouvelle demande de recrutement !'))
                ->setFrom('jobhubwebsiteesprit@gmail.com')
                ->setTo('oussema.makni@esprit.tn')
                ->setBody($this->renderView('demande_recrutement/email.html.twig', ['c' => $job]), 'text/html');

            $mailer->send($message);
        } else {
            $mes = "Job Application Exist";
        }


        $offresapplied = new ArrayCollection();
        $offresapplied = $user->getApplies();
        if ($offresapplied == null) {
            return $this->render('demande_recrutement/appliedjobs.html.twig', [
                'list' => null, 'nb' => 0, 'mes' => "No Applies"
            ]);
        }
        $arr = array();
        $stat = array();
        foreach ($offresapplied->getIterator() as $key => $value) {
            array_push($arr, $value->getOffre()->getId());
            array_push($stat, $value->getStatus());
        }
        $str = "";
        for ($i = 0; $i < count($arr); $i++) {
            if ($i == count($arr) - 1) {
                $str = $str . $arr[$i];
            } else {
                $str = $str . $arr[$i] . ",";
            }
        }

        $donnes = $b->findOff($str);
        $jobs = $pag->paginate($donnes, $request->query->getInt('page', 1), 4);
        $count = $b->countOff($str);

        return $this->render('demande_recrutement/appliedjobs.html.twig', [
            'list' => $jobs, 'nb' => $count, 'mes' => $mes, 'status' => $stat
        ]);
    }

    /**
     * @Route("/delapp/{id}", name="delapp")
     */
    public function delapp($id, Request $request, PaginatorInterface $pag)
    {
        $b = $this->getDoctrine()->getRepository(DemandeRecrutement::class)->delap($id);
        return $this->listapp($this->getUser()->getId(),  $request,  $pag);
    }

    /**
     * @Route("/listapp/{id}", name="listapp")
     */
    public function listapp($id, Request $request, PaginatorInterface $pag)
    {
        $b = $this->getDoctrine()->getRepository(DemandeRecrutement::class);
        $use = $this->getDoctrine()->getRepository(User::class)->find($id);
        $off = $use->getApplies();

        if ($off->isEmpty() || $off == null) {
            return $this->render('demande_recrutement/appliedjobs.html.twig', [
                'list' => null, 'nb' => 0, 'mes' => "No Applies"
            ]);
        }
        $arr = array();
        $stat = array();
        foreach ($off->getIterator() as $key => $value) {
            array_push($arr, $value->getOffre()->getId());
            array_push($stat, $value->getStatus());
        }

        $str = "";
        for ($i = 0; $i < count($arr); $i++) {
            if ($i == count($arr) - 1) {
                $str = $str . $arr[$i];
            } else {
                $str = $str . $arr[$i] . ",";
            }
        }

        $donnes = $b->findOff($str);
        $jobs = $pag->paginate($donnes, $request->query->getInt('page', 1), 4);
        $count = $b->countOff($str);

        return $this->render('demande_recrutement/appliedjobs.html.twig', [
            'list' => $jobs, 'nb' => $count, 'mes' => '', 'status' => $stat
        ]);
    }

    /**
     * @Route("/applyjson", name="applyjson")
     */
    public function addappjson(Request $request, \Swift_Mailer $mailer)
    {
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        $apply = new DemandeRecrutement();

        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $job = $r->find($request->query->get('idoffer'));
        $a = $this->getDoctrine()->getRepository(User::class);
        $user = $a->find($request->query->get('iduser'));
        $b = $this->getDoctrine()->getRepository(DemandeRecrutement::class);

        $apply->setOffre($job);
        $apply->setStatus(false);
        $apply->setCandidat($user);
        $apply->setDateDebut(new \DateTime('now'));
        $exp = new \DateTime('now + 10 day');
        $apply->setDateexpiration($exp);
        if ($b->finddemande($job->getId(), $user->getId()) == null) {
            $em = $this->getDoctrine()->getManager();
            $user->addApply($apply);
            $em->persist($apply);
            $em->flush();
            $message = (new \Swift_Message('Nouvelle demande de recrutement !'))
                ->setFrom('jobhubwebsiteesprit@gmail.com')
                ->setTo('oussema.makni@esprit.tn')
                ->setBody($this->renderView('demande_recrutement/email.html.twig', ['c' => $job]), 'text/html');
            $mailer->send($message);
            $mes = "Job Applied";
        } else {
            $mes = "Job Application Exist";
        }
        return new Response($mes);
    }
}

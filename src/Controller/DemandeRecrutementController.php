<?php

namespace App\Controller;

use App\Entity\DemandeRecrutement;
use App\Entity\OffreEmploi;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;

class DemandeRecrutementController extends AbstractController
{
    /**
     * @Route("/apply/{id}", name="apply")
     */
    public function addapp($id)
    {
        $apply = new DemandeRecrutement();

        $r = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $job = $r->find($id);
        $a = $this->getDoctrine()->getRepository(User::class);
        $user = $a->find(2);
        $b = $this->getDoctrine()->getRepository(DemandeRecrutement::class);

        $apply->setOffre($job);
        $apply->setCandidat($user);
        $apply->setDateDebut(new \DateTime('now'));
        $exp = new \DateTime('now + 10 day');
        //$exp->format('Y-m-d H:i:s');
        //date('Y-m-d H:i:s', strtotime('+1 day', $exp));
        $apply->setDateexpiration($exp);
        if ($b->findBy(['offre' => $id]) == null) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apply);
            $em->flush();
            $this->addFlash(
                'successD',
                'applied to !'
            );
        } else {
            dump("existe");
        }

        $offresapplied = new ArrayCollection();
        $offresapplied = $user->getApplies();
        $arr = array();
        foreach ($offresapplied->getIterator() as $key => $value) {
            array_push($arr, $value->getOffre()->getId());
        }
        $str = "";
        for ($i = 0; $i < count($arr); $i++) {
            if ($i == count($arr) - 1) {
                $str = $str . $arr[$i];
            } else {
                $str = $str . $arr[$i] . ",";
            }
        }

        $offapp = $b->findOff($str);
        $count = $b->countOff($str);

        return $this->render('demande_recrutement/appliedjobs.html.twig', [
            'list' => $offapp, 'nb' => $count
        ]);
    }

    /**
     * @Route("/delapp/{id}", name="delapp")
     */
    public function delapp($id)
    {
        $b = $this->getDoctrine()->getRepository(DemandeRecrutement::class)->delap($id);
        return $this->listapp(2);
    }

    /**
     * @Route("/listapp/{id}", name="listapp")
     */
    public function listapp($id)
    {
        $em = $this->getDoctrine()->getManager();
        $b = $this->getDoctrine()->getRepository(DemandeRecrutement::class);
        $use = $em->getRepository(User::class)
            ->find($id);
        $off = new ArrayCollection();
        $off = $use->getApplies();
        $arr = array();
        foreach ($off->getIterator() as $key => $value) {
            array_push($arr, $value->getOffre()->getId());
        }

        $str = "";
        for ($i = 0; $i < count($arr); $i++) {
            if ($i == count($arr) - 1) {
                $str = $str . $arr[$i];
            } else {
                $str = $str . $arr[$i] . ",";
            }
        }

        $offapp = $b->findOff($str);
        $count = $b->countOff($str);

        return $this->render('demande_recrutement/appliedjobs.html.twig', [
            'list' => $offapp, 'nb' => $count
        ]);
    }
}

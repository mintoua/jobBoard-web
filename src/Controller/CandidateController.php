<?php

namespace App\Controller;

use App\Entity\CandidateResume;
use App\Entity\Education;
use App\Form\CandidateResumeType;
use App\Form\EducationType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/resume",name="resume_")
 */
class CandidateController extends AbstractController
{
    /**
     * @Route("/candidateresume", name="candidateresume")
     * @param Request $request
     * @return Response
     */
    public function addEditResume(Request $request): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $em = $this->getDoctrine()->getManager();
        $loggedUser = $this->getUser();
        $candidateresume = $em->getRepository(CandidateResume::class)->findOneByUserId($loggedUser);
        $education = $em->getRepository(Education::class)->findOneByResume($candidateresume);

        !$candidateresume && $candidateresume = new CandidateResume();
        $form = $this->createForm(CandidateResumeType::class, $candidateresume);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $candidateresume = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $candidateresume->setUserId($loggedUser);
            !$candidateresume->getUserId() && $candidateresume->setUserId($loggedUser);
            $em->persist($candidateresume);
            $em->flush();

            return $this->redirectToRoute('resume_candidateresume');

        }
        return $this->render('candidate/candidateresume.html.twig', [
            'form' => $form->createView(),
            'candidateresume' => $candidateresume,
            'education'=>$education ]);
    }
    /**
     * @param $id
     * @Route("/deleteresume/{id}", name="delete_resume")
     */


    public function deleteResume($id)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');
        }

        $em = $this->getDoctrine()->getManager();
        $resume = $em->getRepository(CandidateResume::class)->find($id);
        if ($resume->getUserId() === $this->getUser()){
            $education = $em->getRepository(Education::class)->findOneByResume($resume);
            if ($education){
                $em->remove($education);

            }
            $em->remove($resume);
            $em->flush();
        }

        return $this->redirectToRoute('resume_candidateresume');

    }


    /**
     * @Route("/resumeEducation/{id}", name="resumeEducation")
     * @param Request $request
     * @param integer $id
     * @return Response
     */
    public function addEditEducation(Request $request,$id): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $em = $this->getDoctrine()->getManager();
        $loggedUser = $this->getUser();
        $resume = $em->getRepository(CandidateResume::class)->find($id);
        $education = $em->getRepository(Education::class)->findOneByResume($resume);
        !$education && $education = new Education();
        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $education = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $education->setResume($resume);
            $em->persist($education);
            $em->flush();
            return $this->redirectToRoute('resume_resumeEducation',array('id'=>$id));

        }
        return $this->render('candidate/resumeEducation.html.twig', [
            'form' => $form->createView(),
            'education' => $education]);

    }





}
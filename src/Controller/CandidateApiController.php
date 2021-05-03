<?php

namespace App\Controller;

use App\Entity\CandidateResume;
use App\Entity\Education;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api",name="resume_")
 */
class CandidateApiController extends AbstractController
{
    /**
     * @Route("/candidateresumeApi", name="candidateresumeApi")
     * @param Request $request
     * @return Response
     * methods={"GET"}
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
        $candidateresume->setResumeHeadline($request->get('ResumeHeadline'));
        $candidateresume->setExperience($request->get('experience'));
        $candidateresume->setSkills($request->get('skills'));
        $candidateresume->setUserId($request->get('userId'));
        $em->persist($candidateresume);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($candidateresume);
        return new JsonResponse($data);
        /*
        $form = $this->createForm(CandidateResumeType::class, $candidateresume);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $candidateresume = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $candidateresume->setUserId($loggedUser);
            !$candidateresume->getUserId() && $candidateresume->setUserId($loggedUser);
            return $this->redirectToRoute('resume_candidateresume');
        }
        return $this->render('candidate/candidateresume.html.twig', [
            'form' => $form->createView(),
            'candidateresume' => $candidateresume,
            'education'=>$education ]);*/
    }
    /**
     * @param $id
     * @Route("/deleteresumeApi/{id}", name="delete_resume")
     * methods={"GET"}
     */
    public function deleteResumeApi($id)
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
      //      $serializer = new Serializer([new ObjectNormalizer()]);
        //    $data = $serializer->normalize($resume);
            return new JsonResponse('deleted');
        }
    }
    /**
     * @Route("/resumeEducationApi/{id}", name="resumeEducationApi")
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
        $education->setCourse($request->get('course'));
        $education->setDateFrom($request->get('dateFrom'));
        $education->setDateTo($request->get('dateTo'));
        $education->setInstitute($request->get('institute'));
        $em->persist($education);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);
        $data = $serializer->normalize($education);
        return new JsonResponse($data);
    }
}
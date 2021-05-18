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
      //  $id = $em->getRepository(f)

        $candidateresume = $em->getRepository(CandidateResume::class)->findOneByUserId($loggedUser);
        $education = $em->getRepository(Education::class)->findOneByResume($candidateresume);
        !$candidateresume && $candidateresume = new CandidateResume();
        $candidateresume->setResumeHeadline($request->get('ResumeHeadline'));
        $candidateresume->setExperience($request->get('experience'));
        $candidateresume->setSkills($request->get('skills'));
    //    $candidateresume->setUserId($request->get('userId',$loggedUser));
        $em->persist($candidateresume);
        $em->flush();
        return new JsonResponse('OK');
    }
    /**
     * @param $id
     * @Route("/deleteresumeApi/{id}", name="deleteresumeApi")
     * methods={"GET"}
     */
    public function deleteResumeApi(Request $request, $id)
    {
//        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
//            $this->redirectToRoute('security_login');
//        }
        $em = $this->getDoctrine()->getManager();
        $resume = $em->getRepository(CandidateResume::class)->find($id);
        if ($resume){
            $em->remove($resume);
            $em->flush();
            return new JsonResponse('Deleted',200);
    }
        return new JsonResponse('Error not found',500);
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
        $education->setDateFrom(new \DateTime($request->get('dateFrom')));
        $education->setDateTo(new \DateTime($request->get('dateTo')));
        $education->setInstitute($request->get('institute'));
        $education->setResume($request->get('resume_id',$resume));
        $em->persist($education);
        $em->flush();
        return new JsonResponse('EDITED');
    }
    /**
     * @Route("/showResumeApi", name="showResumeApi")
     * @param Request $request
     * @return Response
     * methods={"GET"}
     */
    public function showResume(Request $request): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $em = $this->getDoctrine()->getManager();
        $loggedUser = $this->getUser();
        //  $id = $em->getRepository(f)

        $candidateresume = $em->getRepository(CandidateResume::class)->findOneByUserId($loggedUser);
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array(new DateTimeNormalizer(), $normalizer));
        $formatted = $serializer->normalize($candidateresume);
        return new JsonResponse($formatted);
    }
}
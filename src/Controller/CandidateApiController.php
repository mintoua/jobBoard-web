<?php

namespace App\Controller;
use App\Entity\CandidateResume;
use App\Entity\Education;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $candidateresume->setUserId($request->get('userId',$loggedUser));
        $em->persist($candidateresume);
        $em->flush();
        return new JsonResponse('OK');
    }
    /**
     * @param $id
     * @Route("/deleteresumeApi/{id}", name="deleteresumeApi")
     * methods={"GET"}
     */
    public function deleteResumeApi($id)
    {
//        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
//            $this->redirectToRoute('security_login');
//        }
        $em = $this->getDoctrine()->getManager();
        $resume = $em->getRepository(CandidateResume::class)->find($id);
        if ($resume->getUserId() === $this->getUser()){
            $education = $em->getRepository(Education::class)->findOneByResume($resume);
            if ($education){
                $em->remove($education);
            }
            $em->remove($resume);
            $em->flush();
            return new JsonResponse('Deleted');
    }
        return new JsonResponse('Error');
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
}
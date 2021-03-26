<?php

namespace App\Controller;

use App\Entity\CandidateResume;
use App\Entity\Certification;
use App\Form\CandidateResumeType;
use App\Form\CertificationType;
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
    public function addresume(Request $request, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $loggedUser = $this->getUser();
        $candidateresume = $em->getRepository(CandidateResume::class)->findOneByUser($loggedUser);
        !$candidateresume && $candidateresume = new CandidateResume();
        $form = $this->createForm(CandidateResumeType::class, $candidateresume);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $candidateresume = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $candidateresume->setUserId($loggedUser);
            $em->persist($candidateresume);
            $em->flush();
        }
        $formImage = $this->createFormBuilder($candidateresume)
            ->add('Certification', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])->getForm();
        $formImage->handleRequest($request);
        if ($formImage->isSubmitted() && $formImage->isValid()) {
            $certificationFiles = $formImage->get('Certification')->getData();
            foreach ($certificationFiles as $certificationFile) {
                /** @var UploadedFile $certificationFile */
                $newFilename = $fileUploader->upload($certificationFile);
                $certification = new Certification();
                $certification->setImageName($newFilename);
                $candidateresume->addCertification($certification);
            }
            !$candidateresume->getUserId() && $candidateresume->setUserId($loggedUser);
            $em->persist($candidateresume);
            $em->flush();

            return $this->redirectToRoute('candidateresume');

        }
        return $this->render('candidate/candidateresume.html.twig', [
            'form' => $form->createView(),
            'formImage' => $formImage->createView(),
            'candidateresume' => $candidateresume]);

    }
}
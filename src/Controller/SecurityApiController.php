<?php
/**
 * Created by PhpStorm.
 * User: Ryaan
 * Date: 17/01/18
 * Time: 12:34
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\User\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api", name="security_")
 */
class SecurityApiController extends AbstractController
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/loginApi", name="login_api")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * methods={"POST"}
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }
        return $this->json($this->getUser());

    }

    /**
     * @Route("/register", name="register_api", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $form = $this->createForm(RegistrationType::class);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(0);
            $em->persist($user);
            $em->flush();

            return $this->json($user, Response::HTTP_CREATED);
        }

        return $this->json($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/logout", name="logout")
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
        return $this->redirectToRoute('security_login');
    }

}

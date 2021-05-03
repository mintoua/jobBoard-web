<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @Route("/api", name="security_")
 */
class SecurityApiController extends AbstractController
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/loginApiTest", name="loginApiTest")
     * @return Response
     * methods={"GET"}
     */
    public function login(Request $request, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator): Response
    {
     //   $encoderService = $this->container->get('security.password_encoder');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($request->get('email'));
      //  $password = $request->get('password');
    //    $match = $encoderService->isPasswordValid($user, $password);
    //    if($match) {
            $authenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
           );
            return $this->json($this->getUser());
      /*  }
        else
            return $this->json('invalid');*/
    }
    /**
     * @Route("/register", name="register_api")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * methods={"GET"}
     */
    public function register(Request $request,TokenGenerator $tokenGenerator, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
    /*    if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse('Already connected');
        }*/
        $user = new User();
        $user->setFirstName($request->get('firstName'));
        $user->setLastName($request->get('lastName'));
       // $user->setDateOfBirth($request->get('dateOfBirth'));
        $user->setPhone($request->get('phone'));
        $user->setAdresse($request->get('adresse'));
        $user->setProfessionalTitle($request->get('professionalTitle'));
        $user->setPassword($request->get('password'));
        $user->setEmail($request->get('email'));
          //      $token = $tokenGenerator->generateToken();
            //    $user->setToken($request->get($token));
        // $user->setRoles($request->get('rolse',a:1:{i:0;s:9:"ROLE_USER";}));
         $user->setIsActive($request->get('isActive',false));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data = $serializer->normalize($user);
        return new JsonResponse($data);
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

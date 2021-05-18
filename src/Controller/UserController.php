<?php
/**
 * Created by PhpStorm.
 * User: Ryaan
 * Date: 17/01/18
 * Time: 10:14
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\User\RegistrationType;
use App\Form\User\RequestResetPasswordType;
use App\Form\User\ResetPasswordType;
use App\Form\UserType;
use App\Security\LoginFormAuthenticator;
use App\Service\CaptchaValidator;
use App\Service\FileUploader;
use App\Service\Mailer;
use App\Service\TokenGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\File;
/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    const configMail = true;

    /**
     * @Route("/register", name="register")
     * @param FileUploader $fileUploader
     * @param Request $request
     * @param TokenGenerator $tokenGenerator
     * @param UserPasswordEncoderInterface $encoder
     * @param Mailer $mailer
     * @param AuthenticationUtils $authenticationUtils
     * @param CaptchaValidator $captchaValidator
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Throwable
     */
    public function register(FileUploader $fileUploader, Request $request, TokenGenerator $tokenGenerator, UserPasswordEncoderInterface $encoder,
                             Mailer $mailer, AuthenticationUtils $authenticationUtils, CaptchaValidator $captchaValidator, TranslatorInterface $translator)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            try {
                if (!$captchaValidator->validateCaptcha($request->get('g-recaptcha-response'))) {
                    $form->addError(new FormError($translator->trans('captcha.wrong')));
                    throw new ValidatorException('captcha.wrong');
                }
                /**
                 * @var UploadedFile $profileimage
                 */
                $profileimage = $form->get('imageName')->getData();
                if ($profileimage) {
                    $newFilename = $fileUploader->upload($profileimage);
                    $user->setImageName($newFilename);
                }
                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $user->setRoles(array("ROLE_USER"));
                $user->setIsActive(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                if (self::configMail) {
                    $mailer->sendActivationEmailMessage($user);
                    $this->addFlash('success', 'user.activation-link');
                    return $this->redirect($this->generateUrl('homepage'));
                }

                return $this->redirect($this->generateUrl('user_activate', ['token' => $token]));

            } catch (ValidatorException $exception) {

            }
        }
        // last username entered by the user
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('user/security/register.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
            'captchakey' => $captchaValidator->getKey()
        ]);
    }

    /**
     * @Route("/activate/{token}", name="activate")
     * @param $request Request
     * @param $user User
     * @param GuardAuthenticatorHandler $authenticatorHandler
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @return Response
     */
    public function activate(Request $request, User $user, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator): Response
    {
        $user->setIsActive(true);
        $user->setToken(null);
        $user->setActivatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'user.welcome');

        // automatic login
        return $authenticatorHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $loginFormAuthenticator,
            'main'
        );
    }

    /**
     * @Route("/editpassword", name="editpassword")
     * @param $request Request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('security_login'));
        }
         $user = $this->getUser();
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'user.update.success');

        }
        return $this->render('user/security/editpassword.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/request-password-reset", name="request_password_reset")
     * @param Request $request
     * @param TokenGenerator $tokenGenerator
     * @param Mailer $mailer
     * @param CaptchaValidator $captchaValidator
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Throwable
     */
    public function requestPasswordReset(Request $request, TokenGenerator $tokenGenerator, Mailer $mailer,
                                         CaptchaValidator $captchaValidator, TranslatorInterface $translator)
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $form = $this->createForm(RequestResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $repository = $this->getDoctrine()->getRepository(User::class);
                /** @var User $user */
                $user = $repository->findOneBy(['email' => $form->get('_username')->getData(), 'isActive' => true]);
                if (!$user) {
                    $this->addFlash('warning', 'user.not-found');
                    return $this->render('user/security/request-password-reset.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $mailer->sendResetPasswordEmailMessage($user);
                $this->addFlash('success', 'user.request-password-link');
                return $this->redirect($this->generateUrl('homepage'));
            } catch (ValidatorException $exception) {

            }
        }

        return $this->render('user/security/request-password-reset.html.twig', [
            'form' => $form->createView(),
            'captchakey' => $captchaValidator->getKey()
        ]);
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password")
     * @param $request Request
     * @param $user User
     * @param $authenticatorHandler GuardAuthenticatorHandler
     * @param $loginFormAuthenticator LoginFormAuthenticator
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function resetPassword(Request $request, User $user, GuardAuthenticatorHandler $authenticatorHandler,
                                  LoginFormAuthenticator $loginFormAuthenticator, UserPasswordEncoderInterface $encoder)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'user.update.success');
            // automatic login
            return $authenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }

        return $this->render('user/security/password-reset.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/candidateprofile", name="candidateprofile")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return RedirectResponse|Response
     */
    public function editUserInfo(Request $request, FileUploader $fileUploader)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('security_login');}
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $image
             */
            $image = $form->get('imageName')->getData();
            if ($image) {
                $newFilename = $fileUploader->upload($image);
                $user->setImageName($newFilename);
            }
            $em->persist($user);

            $em->flush();
            return $this->redirectToRoute('user_candidateprofile');
        }
        return $this->render("candidate/candidateprofile.html.twig", [
            'form' => $form->createView()
        ]);


    }
    /**
     * @Route("/candidates", name="candidates_list")
     */

    public function ShowCandidates(Request $request, PaginatorInterface $paginator)
    {
        $candidate = $this->getDoctrine()->getRepository(User::class)->findAll();
        $pagination = $paginator->paginate($candidate, $request->query->getInt('page', 1), 5);
        return $this->render('candidate/candidatesProfiles.html.twig', [
            'candidates_list' => $pagination,
        ]);
    }

    /**
     * @Route("/findCandidates", name="candidates_filter")
     * @param Request $request
     * @return Response
     */

    public function findCandidate(Request $request, PaginatorInterface $paginator){
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $professionalTitle = $request->get('professionalTitle');
        $adresse = $request->get('adresse');

        $filtredUsers = $this->findUser($firstName,$lastName,$professionalTitle,$adresse);

        $pagination = $paginator->paginate(
            $filtredUsers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('candidate/candidatesProfiles.html.twig', [
            'candidates_list' => $pagination,
        ]);


    }
    public function findUser($firstName, $lastName, $prof, $adresse)
    {
        $req = $this->getDoctrine()->getManager();
        return $req->createQuery(" select v from App:User v where v.firstName like :fn and v.lastName like :ln and v.adresse like :a and v.professionalTitle like :p")

            ->setParameter('fn', $firstName ? '%'.$firstName.'%' : '%%')
            ->setParameter('ln', $lastName ? '%'.$lastName.'%' : '%%')
            ->setParameter('a', $adresse ? '%'.$adresse.'%' : '%%')
            ->setParameter('p', $prof ? '%'.$prof.'%' : '%%')
            ->getResult();
    }


}

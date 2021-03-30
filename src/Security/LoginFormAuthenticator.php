<?php
/**
 * Created by PhpStorm.
 * User: Ryaan
 * Date: 04/07/16
 * Time: 12:58
 */

namespace App\Security;


use App\Entity\User;

use App\Form\Security\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $em;
    private $formFactory;
    private $passwordEncoder;
    private $router;

    function __construct(EntityManagerInterface $em, FormFactoryInterface $formFactory, UserPasswordEncoderInterface $passwordEncoder, RouterInterface $router)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return bool
     */

    public function supports(Request $request)
    {
        return $request->getPathInfo() == '/login' && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     * @return mixed|null
     */
    public function getCredentials(Request $request)
    {

        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']);

        return $data;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];

        return $this->em->getRepository(User::class)
            ->findOneBy(['email' => $username]);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];

        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        $roles = $token->getUser()->getRoles();
        $hasAccess = in_array('ROLE_ADMIN', $roles);
        if ($hasAccess)
            $redirection = new RedirectResponse($this->router->generate('admin_index'));
        else
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        return $redirection;
    }


    /**
     * @return string
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('homepage');
    }

}

<?php

namespace Duo\SecurityBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
	/**
	 * Login
	 *
	 * @Route("/login", name="duo_security_login")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 * @param AuthenticationUtils $utils
	 *
	 * @return Response
	 */
	public function loginAction(Request $request, AuthenticationUtils $utils): Response
	{
		return $this->render('@DuoSecurity/Form/login.html.twig', [
			'lastUsername' => $utils->getLastUsername(),
			'error' => $utils->getLastAuthenticationError()
		]);
	}

	/**
	 * Forgot password
	 *
	 * @Route("/forgot-password", name="duo_security_forgot_password")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 */
	public function forgotPasswordAction(Request $request)
	{

	}

	/**
	 * Reset password
	 *
	 * @Route("/reset-password", name="duo_security_reset_password")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 */
	public function resetPasswordAction(Request $request)
	{

	}
}

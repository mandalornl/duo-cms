<?php

namespace Duo\SecurityBundle\Controller;

use Duo\AdminBundle\Helper\MailerHelper;
use Duo\SecurityBundle\Form\ForgotPasswordType;
use Duo\SecurityBundle\Form\ResetPasswordType;
use Duo\SecurityBundle\Helper\LoginHelper;
use Duo\SecurityBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/", name="duo_security_")
 */
class SecurityController extends Controller
{
	/**
	 * Forgot password
	 *
	 * @Route("/forgot-password", name="forgot_password", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 * @param UserRepository $userRepository
	 * @param MailerHelper $mailerHelper
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function forgotPasswordAction(
		Request $request,
		UserRepository $userRepository,
		MailerHelper $mailerHelper
	): Response
	{
		$form = $this->createForm(ForgotPasswordType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$data = $form->getData();

			$user = $userRepository->findOneBy([
				'email' => $data['email']
			]);

			if ($user !== null)
			{
				// wait at least one hour before making a new request
				$minimalWaitTime = (new \DateTime())->modify('-1 hour');

				if ($minimalWaitTime < $user->getPasswordRequestedAt())
				{
					$this->addFlash('warning', $this->get('translator')->trans('duo.security.form.forgot_password.wait_message'));
				}
				else
				{
					$token = bin2hex(random_bytes(32));

					$user
						->setPasswordToken($token)
						->setPasswordRequestedAt(new \DateTime());

					$manager = $this->getDoctrine()->getManager();
					$manager->persist($user);
					$manager->flush();

					$message = $mailerHelper
						->prepare('@DuoSecurity/Mail/forgot_password.mjml.twig', [
							'token' => $token
						])
						->setTo($user->getEmail());

					$this->get('mailer')->send($message);

					$this->addFlash('info', $this->get('translator')->trans('duo.security.form.forgot_password.success_message'));

					return $this->redirectToRoute('duo_security_login');
				}
			}
			else
			{
				$this->addFlash('danger', $this->get('translator')->trans('duo.security.form.forgot_password.error_message'));
			}
		}

		return $this->render('@DuoSecurity/Form/forgot_password.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * Login
	 *
	 * @Route("/login", name="login", methods={ "GET", "POST" })
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
	 * Reset password
	 *
	 * @Route("/reset-password/{token}", name="reset_password", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 * @param UserRepository $userRepository
	 * @param LoginHelper $loginHelper
	 * @param MailerHelper $mailerHelper
	 * @param string $token
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function resetPasswordAction(
		Request $request,
		UserRepository $userRepository,
		LoginHelper $loginHelper,
		MailerHelper $mailerHelper,
		string $token
	): Response
	{
		// redirect to password forgot form on invalid token
		if (!$userRepository->passwordTokenExists($token))
		{
			$this->addFlash('danger', $this->get('translator')->trans('duo.security.form.reset_password.error_message'));

			return $this->redirectToRoute('duo_security_forgot_password');
		}

		$form = $this->createForm(ResetPasswordType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$user = $userRepository->findOneByPasswordToken($token);

			if ($user !== null)
			{
				$data = $form->getData();

				$user
					->setPlainPassword($data['password'])
					->setPasswordToken(null)
					->setPasswordRequestedAt(null);

				$manager = $this->getDoctrine()->getManager();
				$manager->persist($user);
				$manager->flush();

				$message = $mailerHelper
					->prepare('@DuoSecurity/Mail/reset_password.mjml.twig')
					->setTo($user->getEmail());

				$this->get('mailer')->send($message);

				$this->addFlash('success', $this->get('translator')->trans('duo.security.form.reset_password.success_message'));

				// log in automatically
				if ($loginHelper->manualLogin($user, 'admin'))
				{
					return $this->redirectToRoute('duo_admin_dashboard_index');
				}

				return $this->redirectToRoute('duo_security_login');
			}

			$this->addFlash('danger', $this->get('translator')->trans('duo.security.form.reset_password.error_message'));
		}

		return $this->render('@DuoSecurity/Form/reset_password.html.twig', [
			'form' => $form->createView(),
			'token' => $token
		]);
	}
}

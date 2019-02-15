<?php

namespace Duo\SecurityBundle\Controller;

use Duo\AdminBundle\Helper\MailerHelper;
use Duo\SecurityBundle\Entity\UserInterface;
use Duo\SecurityBundle\Form\ForgotPasswordType;
use Duo\SecurityBundle\Form\ResetPasswordType;
use Duo\SecurityBundle\Helper\LoginHelper;
use Duo\SecurityBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/**
	 * @var AuthenticationUtils
	 */
	private $authenticationUtils;

	/**
	 * @var LoginHelper
	 */
	private $loginHelper;

	/**
	 * @var MailerHelper
	 */
	private $mailerHelper;

	/**
	 * SecurityController constructor
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 * @param LoginHelper $loginHelper
	 * @param MailerHelper $mailerHelper
	 */
	public function __construct(
		AuthenticationUtils $authenticationUtils,
		LoginHelper $loginHelper,
		MailerHelper $mailerHelper
	)
	{
		$this->authenticationUtils = $authenticationUtils;
		$this->loginHelper = $loginHelper;
		$this->mailerHelper = $mailerHelper;
	}

	/**
	 * Login
	 *
	 * @return Response
	 */
	public function loginAction(): Response
	{
		return $this->render('@DuoSecurity/Form/login.html.twig', [
			'lastUsername' => $this->authenticationUtils->getLastUsername(),
			'error' => $this->authenticationUtils->getLastAuthenticationError()
		]);
	}

	/**
	 * Forgot password
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function forgotPasswordAction(Request $request): Response
	{
		$form = $this->createForm(ForgotPasswordType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$data = $form->getData();

			/**
			 * @var UserInterface $user
			 */
			$user = $this->getDoctrine()->getRepository(UserInterface::class)->findOneBy([
				'email' => $data['email']
			]);

			if ($user !== null)
			{
				// wait at least one hour before making a new request
				$minimalWaitTime = (new \DateTime())->modify('-1 hour');

				if ($minimalWaitTime < $user->getPasswordTokenRequestedAt())
				{
					$this->addFlash('warning', $this->get('translator')->trans('duo_security.forgot_password_wait', [], 'flashes'));
				}
				else
				{
					$token = bin2hex(random_bytes(32));

					$user
						->setPasswordToken($token)
						->setPasswordTokenRequestedAt(new \DateTime());

					$manager = $this->getDoctrine()->getManager();
					$manager->persist($user);
					$manager->flush();

					$message = $this->mailerHelper
						->prepare('@DuoSecurity/Mail/forgot_password.mjml.twig', [
							'token' => $token
						])
						->setTo($user->getEmail());

					$this->get('mailer')->send($message);

					$this->addFlash('info', $this->get('translator')->trans('duo_security.forgot_password_success', [], 'flashes'));

					return $this->redirectToRoute('duo_security_login');
				}
			}
			else
			{
				$this->addFlash('danger', $this->get('translator')->trans('duo_security.forgot_password_error', [], 'flashes'));
			}
		}

		return $this->render('@DuoSecurity/Form/forgot_password.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * Reset password
	 *
	 * @param Request $request
	 * @param string $token
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function resetPasswordAction(Request $request, string $token = null): Response
	{
		/**
		 * @var UserRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(UserInterface::class);

		// redirect to password forgot form on invalid token
		if ($token === null || !$repository->passwordTokenExists($token))
		{
			$this->addFlash('danger', $this->get('translator')->trans('duo_security.reset_password_invalid_token', [], 'flashes'));

			return $this->redirectToRoute('duo_security_forgot_password');
		}

		$form = $this->createForm(ResetPasswordType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			/**
			 * @var UserInterface $user
			 */
			$user = $repository->findOneByPasswordToken($token);

			if ($user !== null)
			{
				$data = $form->getData();

				$user
					->setPlainPassword($data['password'])
					->setPasswordToken(null)
					->setPasswordTokenRequestedAt(null);

				$manager = $this->getDoctrine()->getManager();
				$manager->persist($user);
				$manager->flush();

				$message = $this->mailerHelper
					->prepare('@DuoSecurity/Mail/reset_password.mjml.twig')
					->setTo($user->getEmail());

				$this->get('mailer')->send($message);

				$this->addFlash('success', $this->get('translator')->trans('duo_security.reset_password_success', [], 'flashes'));

				// log in automatically
				if ($this->loginHelper->manualLogin($user, 'admin'))
				{
					return $this->redirectToRoute('duo_admin_dashboard_index');
				}

				return $this->redirectToRoute('duo_security_login');
			}

			$this->addFlash('danger', $this->get('translator')->trans('duo_security.reset_password_error', [], 'flashes'));
		}

		return $this->render('@DuoSecurity/Form/reset_password.html.twig', [
			'form' => $form->createView(),
			'token' => $token
		]);
	}
}

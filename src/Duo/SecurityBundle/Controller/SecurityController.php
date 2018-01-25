<?php

namespace Duo\SecurityBundle\Controller;

use Duo\SecurityBundle\Entity\UserInterface;
use Duo\SecurityBundle\Form\ForgotPasswordType;
use Duo\SecurityBundle\Form\ResetPasswordType;
use Duo\SecurityBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route(name="duo_security_")
 */
class SecurityController extends Controller
{
	/**
	 * Login
	 *
	 * @Route("/login", name="login")
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
	 * @Route("/forgot-password", name="forgot_password")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function forgotPasswordAction(Request $request)
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
				$minimalWaitTime = (new \DateTime())->sub(new \DateInterval('PT1H'));

				if ($minimalWaitTime < $user->getPasswordRequestedAt())
				{
					$this->addFlash('warning', $this->get('translator')->trans('duo.security.form.forgot_password.wait_message'));
				}
				else
				{
					$token = bin2hex(random_bytes(12));

					$user
						->setPasswordToken($token)
						->setPasswordRequestedAt(new \DateTime());

					$em = $this->getDoctrine()->getManager();
					$em->persist($user);
					$em->flush();

					$message = $this->get('duo.admin.mailer_helper')
						->prepare('@DuoSecurity/Mail/forgot_password.mjml.twig', [
							'token' => $user->getPasswordToken()
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
	 * Reset password
	 *
	 * @Route("/reset-password/{token}", name="reset_password")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 * @param string $token
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function resetPasswordAction(Request $request, string $token)
	{
		/**
		 * @var UserRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(UserInterface::class);

		// redirect to password forgot form on invalid token
		if (!$repository->passwordTokenExists($token))
		{
			return $this->redirectToRoute('duo_security_forgot_password');
		}

		$form = $this->createForm(ResetPasswordType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$user = $repository->findOneByPasswordToken($token);

			if ($user !== null)
			{
				$data = $form->getData();

				$user
					->setPlainPassword($data['password'])
					->setPasswordToken(null)
					->setPasswordRequestedAt(null);

				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();

				$message = $this->get('duo.admin.mailer_helper')
					->prepare('@DuoSecurity/Mail/reset_password.mjml.twig')
					->setTo($user->getEmail());

				$this->get('mailer')->send($message);

				$this->addFlash('success', $this->get('translator')->trans('duo.security.form.reset_password.success_message'));

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

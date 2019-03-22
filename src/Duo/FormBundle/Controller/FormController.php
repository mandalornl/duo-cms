<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Helper\MailerHelper;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Entity\FormPart\FormPartInterface;
use Duo\FormBundle\Entity\FormSubmission;
use Duo\FormBundle\Entity\FormTranslation;
use Duo\FormBundle\Event\FormSubmissionEvent;
use Duo\FormBundle\Event\FormSubmissionEvents;
use Duo\FormBundle\Form\FormViewType;
use Duo\FormBundle\Form\Type\RecaptchaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormController extends AbstractController
{
	/**
	 * @var MailerHelper
	 */
	private $mailerHelper;

	/**
	 * FormController constructor
	 *
	 * @param MailerHelper $mailerHelper
	 */
	public function __construct(MailerHelper $mailerHelper)
	{
		$this->mailerHelper = $mailerHelper;
	}

	/**
	 * View action
	 *
	 * @param Request $request
	 * @param string $uuid
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function viewAction(Request $request, string $uuid): Response
	{
		$className = Form::class;

		/**
		 * @var Form $entity
		 */
		$entity = $this->getDoctrine()->getRepository($className)->findOneBy([
			'uuid' => $uuid
		]);

		if ($entity === null)
		{
			return $this->json([
				'error' => "Entity '{$className}::{$uuid}' not found",
				'message' => $this->get('translator')->trans('duo_admin.error', [], 'flashes')
			]);
		}

		$formName = str_replace('-', '_', "form_{$entity->getUuid()}");

		$form = $this->get('form.factory')->createNamed($formName, FormViewType::class, null, [
			'action' => $this->generateUrl('duo_form_view', [
				'uuid' => $entity->getUuid(),
				'locale' => $request->getLocale()
			]),
			'fields' => $entity->translate($request->getLocale())->getParts()
		]);

		// add recaptcha
		$form->add('g-recaptcha-response', RecaptchaType::class, [
			'expectedAction' => $formName,
			'remoteIp' => $request->getClientIp(),
			'useRecaptchaNet' => true,
			'autoHideBadge' => true
		]);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$data = $form->getData();

			$submissionData = [];

			foreach ($entity->translate($request->getLocale())->getParts() as $index => $part)
			{
				if (!isset($data[$index]))
				{
					continue;
				}

				/**
				 * @var FormPartInterface $part
				 */
				$submissionData[$part->getLabel()] = trim($data[$index]);
			}

			$submission = (new FormSubmission())
				->setName($entity->getName())
				->setLocale($request->getLocale())
				->setData($submissionData)
				->setForm($entity);

			// keep submission
			if ($entity->getKeepSubmissions())
			{
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($submission);
				$manager->flush();
			}

			// send submission
			if ($entity->getSendSubmissionsTo())
			{
				$message = $this->mailerHelper
					->prepare('@DuoForm/Mail/form_submission.mjml.twig', [
						'entity' => $entity,
						'submission' => $submission
					])
					->setTo($entity->getSendSubmissionsTo());

				$this->get('mailer')->send($message);
			}

			// dispatch event
			$this->get('event_dispatcher')->dispatch(
				FormSubmissionEvents::SUBMIT,
				new FormSubmissionEvent($submission, $form)
			);

			/**
			 * @var FormTranslation $translation
			 */
			$translation = $entity->translate($request->getLocale());

			return $this->json([
				'message' => $translation->getMessage(),
				'redirect' => $entity->getPage() !== null ? $this->generateUrl('_url', [
					'url' => $entity->getPage()->translate($request->getLocale())->getUrl(),
					'_locale' => $request->getLocale()
				]) : null
			]);
		}

		return $this->json([
			'html' => $this->renderView('@DuoForm/Form/view.html.twig', [
				'form' => $form->createView()
			])
		]);
	}
}

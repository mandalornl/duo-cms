<?php

namespace Duo\FormBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\AdminBundle\Helper\MailerHelper;
use Duo\CoreBundle\Entity\TranslateInterface;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Entity\FormPartInterface;
use Duo\FormBundle\Entity\FormSubmission;
use Duo\FormBundle\Entity\FormTranslation;
use Duo\FormBundle\Form\FormViewType;
use Duo\PartBundle\Entity\EntityPartInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/form-view", name="duo_form_view_")
 */
class FormViewController extends Controller
{
	/**
	 * View action
	 *
	 * @Route("/{id}", name="form", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 * @param int $id
	 * @param MailerHelper $mailerHelper
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function viewAction(Request $request, int $id, MailerHelper $mailerHelper): JsonResponse
	{
		$class = Form::class;

		/**
		 * @var Form $entity
		 */
		$entity = $this->getDoctrine()->getRepository($class)->find($id);

		if ($entity === null)
		{
			return $this->json([
				'error' => "Entity '{$class}::{$id}' not found"
			]);
		}

		$formParts = $this->getFormParts($request, $entity);

		if ($formParts === null)
		{
			$interface = EntityPartInterface::class;
			$error = "Entity '{$class}::{$id}' doesn't implement '{$interface}'";

			return $this->json([
				'error' => $error
			]);
		}

		$form = $this->createForm(FormViewType::class, null, [
			'action' => $this->generateUrl('duo_form_view_form', [
				'id' => $id,
				'locale' => $request->getLocale()
			]),
			'formParts' => $formParts
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$data = $form->getData();

			$submissionData = [];

			foreach ($formParts as $index => $formPart)
			{
				if (!isset($data[$index]))
				{
					continue;
				}

				/**
				 * @var FormPartInterface $formPart
				 */
				$submissionData[$formPart->getLabel()] = trim($data[$index]);
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

			/**
			 * @var FormTranslation $translation
			 */
			$translation = $entity->translate($request->getLocale());

			// send submission to
			if ($entity->getSendSubmissionsTo() && !count($this->get('validator')->validateProperty($entity, 'sendSubmissionTo')))
			{
				$message = $mailerHelper
					->prepare('@DuoForm/Mail/form_submission.mjml.twig', [
						'entity' => $entity,
						'submission' => $submission
					])
					->setTo($entity->getSendSubmissionsTo());

				$this->get('mailer')->send($message);
			}

			return $this->json([
				'success' => true,
				'message' => $translation->getMessage(),
				'url' => $entity->getPage() !== null ? $this->generateUrl('url', [
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

	/**
	 * Get form parts
	 *
	 * @param Request $request
	 * @param Form $entity
	 *
	 * @return ArrayCollection
	 */
	private function getFormParts(Request $request, Form $entity): ArrayCollection
	{
		if (!$entity instanceof EntityPartInterface)
		{
			if ($entity instanceof TranslateInterface)
			{
				$translation = $entity->translate($request->getLocale());
				if ($translation instanceof EntityPartInterface)
				{
					return $translation->getParts();
				}
			}

			return null;
		}

		/**
		 * @var EntityPartInterface $entity
		 */
		return $entity->getParts();
	}
}
<?php

namespace Duo\FormBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Duo\BehaviorBundle\Entity\TranslateInterface;
use Duo\FormBundle\Entity\Form;
use Duo\FormBundle\Entity\FormPartInterface;
use Duo\FormBundle\Entity\FormSubmission;
use Duo\FormBundle\Entity\FormTranslation;
use Duo\FormBundle\Form\FormViewType;
use Duo\PartBundle\Entity\EntityPartInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="duo_form_view_")
 */
class FormViewController extends Controller
{
	/**
	 * View action
	 *
	 * @Route("/{id}", name="form")
	 * @Method({"POST", "GET"})
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function viewAction(Request $request, int $id): JsonResponse
	{
		$entity = $this->getDoctrine()->getRepository(Form::class)->find($id);

		if ($entity === null)
		{
			$class = Form::class;

			return $this->json([
				'result' => [
					'success' => false,
					'error' => "Entity '{$class}:{$id}' not found"
				]
			]);
		}

		$formParts = $this->getFormParts($request, $entity);

		$form = $this->createForm(FormViewType::class, null, [
			'action' => $this->generateUrl('duo_form_view_form', [
				'id' => $id
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

			$em = $this->getDoctrine()->getManager();
			$em->persist($submission);
			$em->flush();

			/**
			 * @var FormTranslation $translation
			 */
			$translation = $entity->translate($request->getLocale());

			$message = $this->get('duo.admin.mailer_helper')
				->prepare('@DuoForm/Mail/form_submission.mjml.twig', [
					'submission' => $submission
				])
				->setTo($entity->getEmailTo());

			$this->get('mailer')->send($message);

			return $this->json([
				'result' => [
					'success' => true,
					'message' => $translation->getMessage()
				]
			]);
		}

		return $this->json([
			'result' => [
				'html' => $this->renderView('@DuoForm/Form/view.html.twig', [
					'form' => $form->createView()
				])
			]
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
		if ($entity instanceof TranslateInterface)
		{
			$translation = $entity->translate($request->getLocale());
			if ($translation instanceof EntityPartInterface)
			{
				return $translation->getParts();
			}
		}

		/**
		 * @var EntityPartInterface $entity
		 */
		return $entity->getParts();
	}
}
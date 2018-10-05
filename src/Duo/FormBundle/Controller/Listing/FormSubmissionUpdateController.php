<?php

namespace Duo\FormBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractUpdateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form-submission", name="duo_form_listing_submission_")
 */
class FormSubmissionUpdateController extends AbstractUpdateController
{
	use FormSubmissionConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/{id}.{_format}",
	 *     name="update",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *	   defaults={ "_format" = "html" },
	 *     methods={ "GET" }
	 * )
	 */
	public function updateAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		return $this->render($this->getUpdateTemplate(), (array)$this->getDefaultContext([
			'entity' => $entity
		]));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getUpdateTemplate(): string
	{
		return '@DuoForm/Listing/update.html.twig';
	}
}
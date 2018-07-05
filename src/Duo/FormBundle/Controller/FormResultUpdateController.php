<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractUpdateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form-result", name="duo_form_listing_result_")
 */
class FormResultUpdateController extends AbstractUpdateController
{
	use FormResultConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="update", requirements={ "id" = "\d+" }, methods={ "GET" })
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
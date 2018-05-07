<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractEditController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form-result", name="duo_form_listing_result_")
 */
class FormResultEditController extends AbstractEditController
{
	use FormResultConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" }, methods={ "GET" })
	 */
	public function editAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		return $this->render($this->getEditTemplate(), (array)$this->getDefaultContext([
			'entity' => $entity
		]));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getEditTemplate(): string
	{
		return '@DuoForm/Listing/edit.html.twig';
	}
}
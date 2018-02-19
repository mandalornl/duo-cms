<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractEditController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
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
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" })
	 * @Method("GET")
	 */
	public function editAction(Request $request, int $id)
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
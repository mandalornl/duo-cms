<?php

namespace Duo\FormBundle\Controller\Modal;

use Duo\FormBundle\Form\Type\FormPartCollectionType;
use Duo\PartBundle\Controller\Modal\AbstractPartController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/form/modal", name="duo_form_modal_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class FormPartController extends AbstractPartController
{
	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): string
	{
		return FormPartCollectionType::class;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @Route("/part-prototype", name="part_prototype", methods={ "GET" })
	 */
	public function modelAction(Request $request): JsonResponse
	{
		return $this->doModalAction($request);
	}
}

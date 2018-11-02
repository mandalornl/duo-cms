<?php

namespace Duo\FormBundle\Controller\Modal;

use Duo\FormBundle\Form\Type\FormPartCollectionType;
use Duo\PartBundle\Controller\Modal\AbstractPartController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/form-part", name="duo_form_part_modal_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class FormPartController extends AbstractPartController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): string
	{
		return FormPartCollectionType::class;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/prototype", name="prototype", methods={ "GET" })
	 */
	public function modelAction(Request $request): JsonResponse
	{
		return $this->doModalAction($request);
	}
}
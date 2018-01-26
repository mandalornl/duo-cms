<?php

namespace Duo\FormBundle\Controller;

use Duo\FormBundle\Form\FormPartCollectionType;
use Duo\PartBundle\Controller\AbstractPartModalController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="duo_form_part_modal_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class FormPartModalController extends AbstractPartModalController
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
	 * @Route("/prototype", name="prototype")
	 * @Method("GET")
	 */
	public function modelAction(Request $request): JsonResponse
	{
		return $this->doModalAction($request);
	}
}
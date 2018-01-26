<?php

namespace Duo\PageBundle\Controller;

use Duo\PageBundle\Form\PagePartCollectionType;
use Duo\PartBundle\Controller\AbstractPartModalController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="duo_page_part_modal_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PagePartModalController extends AbstractPartModalController
{
	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): string
	{
		return PagePartCollectionType::class;
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
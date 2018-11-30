<?php

namespace Duo\PageBundle\Controller\Modal;

use Duo\PageBundle\Form\Type\PagePartCollectionType;
use Duo\PartBundle\Controller\Modal\AbstractPartController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/page/modal", name="duo_page_modal_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PagePartController extends AbstractPartController
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
	 * @Route("/part-prototype", name="part_prototype", methods={ "GET" })
	 */
	public function modelAction(Request $request): JsonResponse
	{
		return $this->doModalAction($request);
	}
}

<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Controller\AbstractSortController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageSortController extends AbstractSortController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-up/{id}", name="move_up", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function moveUpAction(Request $request, int $id)
	{
		return $this->doMoveUpAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-down/{id}", name="move_down", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function moveDownAction(Request $request, int $id)
	{
		return $this->doMoveDownAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/move-to/{_format}", name="move_to", defaults={ "_format" = "html" }, requirements={ "_format" = "html|json" })
	 * @Method({"GET", "POST"})
	 */
	public function moveToAction(Request $request)
	{
		return $this->doMoveToAction($request);
	}
}
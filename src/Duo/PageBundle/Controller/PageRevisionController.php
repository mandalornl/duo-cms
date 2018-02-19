<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Controller\AbstractRevisionController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageRevisionController extends AbstractRevisionController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/revision/{id}/", name="revision", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function revisionAction(Request $request, int $id)
	{
		return $this->doRevisionAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/revert/{id}", name="revert", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function revertAction(Request $request, int $id)
	{
		return $this->doRevertAction($request, $id);
	}
}
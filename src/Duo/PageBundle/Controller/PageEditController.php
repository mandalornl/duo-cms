<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Controller\AbstractEditController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageEditController extends AbstractEditController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function editAction(Request $request, int $id): Response
	{
		return $this->doEditAction($request, $id);
	}
}
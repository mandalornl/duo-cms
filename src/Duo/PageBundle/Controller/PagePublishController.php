<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Controller\AbstractPublishController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PagePublishController extends AbstractPublishController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/publish/{id}", name="publish", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function publishAction(Request $request, int $id): Response
	{
		return $this->doPublishAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/unpublish/{id}", name="unpublish", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function unpublishAction(Request $request, int $id): Response
	{
		return $this->doUnpublishAction($request, $id);
	}
}
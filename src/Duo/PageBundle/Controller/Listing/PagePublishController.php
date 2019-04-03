<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\CoreBundle\Controller\Listing\AbstractPublishController;
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
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/publish/{id}.{_format}",
	 *     name="publish",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function publishAction(Request $request, int $id): Response
	{
		return $this->doPublishAction($request, $id);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/unpublish/{id}.{_format}",
	 *     name="unpublish",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function unpublishAction(Request $request, int $id): Response
	{
		return $this->doUnpublishAction($request, $id);
	}
}

<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractCreateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageCreateController extends AbstractCreateController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/create.{_format}",
	 *     name="create",
	 *     requirements={ "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function createAction(Request $request): Response
	{
		return $this->doCreateAction($request);
	}
}

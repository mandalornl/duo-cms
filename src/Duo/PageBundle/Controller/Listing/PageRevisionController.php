<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\CoreBundle\Controller\Listing\AbstractRevisionController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
	 * @Route(
	 *     path="/revision/view/{id}",
	 *     name="revision_view",
	 *     requirements={ "id" = "\d+" },
	 *     methods={ "GET" }
	 * )
	 */
	public function viewAction(Request $request, int $id): Response
	{
		return $this->doViewAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/revision/revert/{id}.{_format}",
	 *     name="revision_revert",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function revertAction(Request $request, int $id): Response
	{
		return $this->doRevertAction($request, $id);
	}
}
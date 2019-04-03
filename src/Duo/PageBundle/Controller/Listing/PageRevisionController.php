<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\CoreBundle\Controller\Listing\AbstractRevisionController;
use Duo\PageBundle\Entity\PageRevision;
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
	 * {@inheritDoc}
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
	 * {@inheritDoc}
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

	/**
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/revision/destroy/{id}.{_format}",
	 *     name="revision_destroy",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function destroyAction(Request $request, int $id): Response
	{
		return $this->doDestroyAction($request, $id);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getRevisionEntityClass(): string
	{
		return PageRevision::class;
	}
}

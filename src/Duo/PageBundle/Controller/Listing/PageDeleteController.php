<?php

namespace Duo\PageBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractDeleteController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageDeleteController extends AbstractDeleteController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/delete/{id}.{_format}",
	 *     name="delete",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function deleteAction(Request $request, int $id = null): Response
	{
		return $this->doDeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/undelete/{id}.{_format}",
	 *     name="undelete",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *	   defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function undeleteAction(Request $request, int $id = null): Response
	{
		return $this->doUndeleteAction($request, $id);
	}
}
<?php

namespace Duo\TaxonomyBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractUpdateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/module/taxonomy", name="duo_taxonomy_listing_taxonomy_")
 */
class TaxonomyUpdateController extends AbstractUpdateController
{
	use TaxonomyConfigurationTrait;

	/**
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/{id}.{_format}",
	 *     name="update",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function updateAction(Request $request, int $id): Response
	{
		return $this->doUpdateAction($request, $id);
	}
}

<?php

namespace Duo\SeoBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractUpdateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/redirect", name="duo_seo_listing_redirect_")
 */
class RedirectUpdateController extends AbstractUpdateController
{
	use RedirectConfigurationTrait;

	/**
	 * {@inheritdoc}
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
<?php

namespace Duo\MediaBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractCreateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaCreateController extends AbstractCreateController
{
	use MediaConfigurationTrait;

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

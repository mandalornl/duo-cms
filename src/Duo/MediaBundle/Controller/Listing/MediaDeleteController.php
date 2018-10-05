<?php

namespace Duo\MediaBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractDeleteController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaDeleteController extends AbstractDeleteController
{
	use MediaConfigurationTrait;

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
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function undeleteAction(Request $request, int $id = null): Response
	{
		return $this->doUndeleteAction($request, $id);
	}
}
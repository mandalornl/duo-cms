<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractUpdateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaUpdateController extends AbstractUpdateController
{
	use MediaConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="update", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function updateAction(Request $request, int $id): Response
	{
		return $this->doUpdateAction($request, $id);
	}
}
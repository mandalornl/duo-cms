<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDestroyController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/media", name="duo_media_listing_media_")
 */
class MediaDestroyController extends AbstractDestroyController
{
	use MediaConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function destroyAction(Request $request, int $id = null): Response
	{
		return $this->doDestroyAction($request, $id);
	}
}